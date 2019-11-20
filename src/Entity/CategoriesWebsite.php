<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesWebsiteRepository")
 */
class CategoriesWebsite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\NotNull
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    private $category_slug_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WebsitePosts", mappedBy="category_id")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategorySlugId(): ?int
    {
        return $this->category_slug_id;
    }

    public function setCategorySlugId(int $category_slug_id): self
    {
        $this->category_slug_id = $category_slug_id;

        return $this;
    }

    /**
     * @return Collection|WebsitePosts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(WebsitePosts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategoryId($this);
        }

        return $this;
    }

    public function removePost(WebsitePosts $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCategoryId() === $this) {
                $post->setCategoryId(null);
            }
        }

        return $this;
    }
}
