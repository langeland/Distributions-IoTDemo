<?php
namespace Peytz\Vote\Controller;

/*
 * This file is part of the Peytz.Vote package.
 */

use TYPO3\Flow\Annotations as Flow;
use Peytz\Vote\Domain\Model\Vote;

class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController
{

    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'json' => 'TYPO3\Flow\Mvc\View\JsonView'
    );

    /**
     * @var \Peytz\Vote\Domain\Repository\VoteRepository
     * @Flow\Inject
     */
    protected $voteRepository;

    /**
     * @var \TYPO3\Flow\Session\SessionInterface
     * @Flow\Inject
     */
    protected $session;

    /**
     * @return void
     */
    public function indexAction()
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        if ($this->session->getData('hasVoted')) {
            $this->addFlashMessage('You can only vote once.');
            $this->redirect('thanks');
        }
    }

    /**
     * @param \Peytz\Vote\Domain\Model\Vote $newVote
     * @return void
     */
    public function registerAction(Vote $newVote)
    {
        $newVote->setDate(new \DateTime());
        $this->voteRepository->add($newVote);

        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $this->session->putData('hasVoted', TRUE);

        $this->addFlashMessage('Vote registered.');
        $this->redirect('thanks');
    }

    /**
     * @return void
     */
    public function thanksAction()
    {

    }

    /**
     * @return void
     */
    public function resultAction()
    {
        $votes = $this->voteRepository->findAll();
        $voteSum = 0;
        /** @var \Peytz\Vote\Domain\Model\Vote $vote */
        foreach ($votes as $vote) {
            $voteSum = $voteSum + $vote->getValue();
        }
        $voteResult = $voteSum / $votes->count();

        $this->view->assign('value', array('status' => 200, 'result' => $voteResult));
    }


}
