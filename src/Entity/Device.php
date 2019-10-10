<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type("string")
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage = "Your first name must be at least {{ limit }} characters long",
     *     maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotNull
     * @Assert\Ip
     * @Assert\Length(
     *      min = 2,
     *      max = 16,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $ip_address;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull
     * @Assert\Type("bool")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DataMain", mappedBy="device")
     */
    private $DataMains;

    public function __construct()
    {
        $this->DataMains = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getActive(): Boolean
    {
        return $this->active;
    }

    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|DataMain[]
     */
    public function getDataMains(): Collection
    {
        return $this->DataMains;
    }

    public function addDataMain(DataMain $dataMain): self
    {
        if (!$this->DataMains->contains($dataMain)) {
            $this->DataMains[] = $dataMain;
            $dataMain->setDevice($this);
        }

        return $this;
    }

    public function removeDataMain(DataMain $dataMain): self
    {
        if ($this->DataMains->contains($dataMain)) {
            $this->DataMains->removeElement($dataMain);
            // set the owning side to null (unless already changed)
            if ($dataMain->getDevice() === $this) {
                $dataMain->setDevice(null);
            }
        }

        return $this;
    }


}
