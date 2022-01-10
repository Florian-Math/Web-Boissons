<?php

namespace App\Entity;

use App\Repository\HierarchieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HierarchieRepository::class)
 */
class Hierarchie
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $nomAliment;


    public function getNomAliment(): ?string
    {
        return $this->nomAliment;
    }

    public function setNomAliment(string $nomAliment): self
    {
        $this->nomAliment = $nomAliment;

        return $this;
    }
}
