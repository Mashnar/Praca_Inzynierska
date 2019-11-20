<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsitePostsRepository")
 */
class WebsitePosts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoriesWebsite", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $posts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?CategoriesWebsite
    {
        return $this->category_id;
    }

    public function setCategoryId(?CategoriesWebsite $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getPosts(): ?string
    {
        return $this->posts;
    }

    public function setPosts(string $posts): self
    {
        $this->posts = $posts;

        return $this;
    }
}
