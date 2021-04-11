<?php 
// src/AppBundle/Entity/Likes.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Likes")
 */
class Likes
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article",inversedBy="likedArticle")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $article; 

    /** 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userLikes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;


    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setLike($user, $article)
    {
        $this->user = $user;
        $this->article = $article;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUserLike()
    {
        return $this->user;
    }

    /**
     * Get the value of article
     */ 
    public function getArticleLike()
    {
        return $this->article;
    }

}

?>