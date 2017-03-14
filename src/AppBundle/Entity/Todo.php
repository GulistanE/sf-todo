<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Todo
 */
class Todo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $trashed;

    /**
     * @var \DateTime
     */
    private $date;

     /**
     * Constructor.
     */
    public function __construct()
    {
        $this->validated = false;
        $this->date = new \DateTime();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Todo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set trashed
     *
     * @param boolean $trashed
     * @return Todo
     */
    public function setTrashed($trashed)
    {
        $this->trashed = $trashed;

        return $this;
    }

    /**
     * Get trashed
     *
     * @return boolean 
     */
    public function getTrashed()
    {
        return $this->trashed;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Todo
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

}
