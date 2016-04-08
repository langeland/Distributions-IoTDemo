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
        $this->view->assign('node',$node);
        $this->view->assign('value', array('status' => $node->getStatus()));
    }

    /**
     * @param Node $node
     * @return void
     */
    public function updateAction(Node $node)
    {
        $this->addFlashMessage('Node updated.');
        $this->nodeRepository->update($node);
        $this->redirect('show', null, null, array('node'=>$node));
    }
}
