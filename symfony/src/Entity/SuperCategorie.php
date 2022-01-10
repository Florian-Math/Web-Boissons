<?php

namespace App\Entity;

use App\Repository\SuperCategorieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuperCategorieRepository::class)
 */
class SuperCategorie
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
    private $superCategorie;

    public function getAliment(): ?string
    {
        return $this->aliment;
    }

    public function setAliment(string $aliment): self
    {
        $this->aliment = $aliment;

        return $this;
    }

    public function getSuperCategorie(): ?string
    {
        return $this->superCategorie;
    }

    public function setSuperCategorie(string $superCategorie): self
    {
        $this->superCategorie = $superCategorie;

        return $this;
    }
}
