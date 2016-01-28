<?php
namespace Peytz\Vote\Domain\Model;

/*
 * This file is part of the Peytz.Vote package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Vote
{
    /**
     * @var integer
     * @Flow\Validate(type="NumberRange", minimum=0, maximum=9)
     */
    protected $value;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $session;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}
