<?php

namespace App\Entity;

use App\Repository\SousCategorieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SousCategorieRepository::class)
 */
class SousCategorie
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $aliment;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $sousCategorie;

    public function getAliment(): ?string
    {
        return $this->aliment;
    }

    public function setAliment(string $aliment): self
    {
        $this->aliment = $aliment;

        return $this;
    }

    public function getSousCategorie(): ?string
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(string $sousCategorie): self
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }
}
