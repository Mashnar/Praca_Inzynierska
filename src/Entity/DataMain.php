<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataMainRepository")
 */
class DataMain
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Device", inversedBy="DataMains")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $device;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $humidity;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $pressure;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $pm25;
    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $pm10;


    public function getPm25()
    {
        return $this->pm25;
    }


    public function setPm25(float $pm25): self
    {
        $this->pm25 = $pm25;

        return $this;
    }


    public function getPm10()
    {
        return $this->pm10;
    }


    public function setPm10(float $pm10): self
    {
        $this->pm10 = $pm10;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }
}
