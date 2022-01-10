<?php

namespace App\Entity;

use App\Repository\ComposantRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComposantRecetteRepository::class)
 */
class ComposantRecette
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $idRecette;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $aliment;

    public function getIdRecette(): ?int
    {
        return $this->idRecette;
    }

    public function setIdRecette(int $idRecette): self
    {
        $this->idRecette = $idRecette;

        return $this;
    }

    public function getAliment(): ?string
    {
        return $this->aliment;
    }

    public function setAliment(string $aliment): self
    {
        $this->aliment = $aliment;

        return $this;
    }
}
