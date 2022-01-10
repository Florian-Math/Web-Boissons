<?php

namespace App\Entity;

use App\Repository\AlimentAlimentDepthRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlimentAlimentDepthRepository::class)
 */
class AlimentAlimentDepth
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $aliment1;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $aliment2;

    /**
     * @ORM\Column(type="integer")
     */
    private $depth;

    public function getAliment1(): ?string
    {
        return $this->aliment1;
    }

    public function setAliment1(string $aliment1): self
    {
        $this->aliment1 = $aliment1;

        return $this;
    }

    public function getAliment2(): ?string
    {
        return $this->aliment2;
    }

    public function setAliment2(string $aliment2): self
    {
        $this->aliment2 = $aliment2;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }
}
