<?php


namespace App\Controller;


use App\Entity\Recette;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    /**
     * @Route("/recette/{id}", name="recette")
     */
    public function index(Request $request, ManagerRegistry $doctrine, int $id): Response{

        $recette = $doctrine->getRepository(Recette::class)->find($id);

        return $this->render('recette.html.twig', [
            'recette' => $recette
        ]);
    }

}