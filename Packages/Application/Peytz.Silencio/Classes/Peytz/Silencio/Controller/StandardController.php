<?php
namespace Peytz\Silencio\Controller;

/*
 * This file is part of the Peytz.Silencio package.
 */

use TYPO3\Flow\Annotations as Flow;
use Peytz\Silencio\Domain\Model\Node;
use Peytz\Silencio\Domain\Repository\NodeRepository;

class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'json' => 'TYPO3\Flow\Mvc\View\JsonView'
    );

    /**
     * @var NodeRepository
     * @Flow\Inject
     */
    protected $nodeRepository;

    /**
     * @return void
     */
    public function indexAction()
    {
        $nods = $this->nodeRepository->findAll();
        $this->view->assign('nodes', $nods);
    }

    /**
     * @param Node $node
     * @return void
     */
    public function showAction(Node $node)
    {
        $colors = array(
            0 => '#00ff00',
            1 => '#0000ff',
            2 => '#ffff00',
            3 => '#ff0000'
        );

        $this->view->assign('node', $node);
        $this->view->assign('value',
            array(
                'status' => $node->getStatus(),
                'color' => $this->hex2RGB($colors[$node->getStatus()])
            )
        );
    }

    /**
     * @param Node $node
     * @return void
     */
    public function updateAction(Node $node)
    {
        $this->addFlashMessage('Node updated.');
        $this->nodeRepository->update($node);
        $this->redirect('show', null, null, array('node' => $node));
    }

    /**
     * Convert a hexa decimal color code to its RGB equivalent
     *
     * @param string $hexStr (hexadecimal color value)
     * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
     * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
     * @return array or string (depending on second parameter. Returns False if invalid hex color value)
     */
    function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }
}
