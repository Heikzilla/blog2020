<?php
// src/App/Entity/Article.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Article")
 */
class Article
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    protected $text;

    /**
     * @var \DateTime $timestamp
     *
     * @ORM\Column(type="datetime")
     */
    protected $dueTime;

    /** 
     * @ORM\ManyToOne(targetEntity="Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublic;

    /**
     * Get the value of id
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of text
     */ 
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */ 
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of dueTime
     */ 
    public function getDueTime()
    {
        return $this->dueTime;
    }

    /**
     * Set the value of dueTime
     *
     * @return  self
     */ 
    public function setDueTime($dueTime)
    {
        $this->dueTime = $dueTime;

        return $this;
    }

    /**
     * Get the value of isPublic
     */ 
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set the value of isPublic
     *
     * @return  self
     */ 
    public function setIsPublic($isPublic = TRUE)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
