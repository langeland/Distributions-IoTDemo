<?php
namespace Peytz\Vote\Domain\Repository;

/*
 * This file is part of the Peytz.Vote package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class VoteRepository extends Repository
{

    // add customized methods here

    /**
     * @return \TYPO3\Flow\Persistence\QueryResultInterface The query result
     */
    public function findActive(){
        $query = $this->createQuery();

        $threshold = new \DateTime();
        $threshold->modify('-5 minutes');



        return $query->matching(
            $query->greaterThanOrEqual('date', $threshold)
        )->execute();



    }

}
