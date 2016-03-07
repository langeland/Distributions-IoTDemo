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
        /*
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        if ($this->session->getData('hasVoted')) {
            $this->addFlashMessage('You can only vote once.');
            $this->redirect('thanks');
        }
        */
    }

    /**
     * @param \Peytz\Vote\Domain\Model\Vote $newVote
     * @return void
     */
    public function registerAction(Vote $newVote)
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        /** @var \Peytz\Vote\Domain\Model\Vote $vote */
        if ($vote = $this->voteRepository->findOneBySession($this->session->getId())) {
            $vote->setDate(new \DateTime());
            $vote->setValue($newVote->getValue());
            $this->voteRepository->update($vote);
        } else {

            $newVote->setDate(new \DateTime());
            $newVote->setSession($this->session->getId());
            $this->voteRepository->add($newVote);
        }

        $this->session->putData('hasVoted', true);
        $this->addFlashMessage('Vote registered.');
        $this->redirect('index');
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
        $votes = $this->voteRepository->findActive();
        $voteSum = 0;
        $voteResult = 5;
        $voteCount = $votes->count();
        /** @var \Peytz\Vote\Domain\Model\Vote $vote */
        foreach ($votes as $vote) {
            $voteSum = $voteSum + $vote->getValue();
        }

        if ($votes->count() > 0) {
            $voteResult = $voteSum / $votes->count();
        }

        $this->view->assign('value', array('status' => 200, 'result' => $voteResult));
        $this->view->assign('voteCount', $voteCount);
    }
}
