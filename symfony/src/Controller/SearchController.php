<?php


namespace App\Controller;


use App\Entity\AlimentAlimentDepth;
use App\Entity\ComposantRecette;
use App\Entity\Hierarchie;
use App\Entity\Recette;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response{

        $data = [];

        $aliments = $doctrine->getRepository(Hierarchie::class)->findAll();
        foreach ($aliments as $aliment){
            $data[$aliment->getNomAliment()] = null;
        }

        return $this->render('search.html.twig', [
            'aliments' => $data
        ]);
    }

    /**
     * @Route("/Ajax/getRecettes", name="getRecettes")
     */
    public function getRecettes(Request $request, ManagerRegistry $doctrine){

        if(!$request->isXmlHttpRequest() || !$request->isMethod("POST")) throw new NotFoundHttpException();

        $tags = $request->get('tags');
        $ntags = $request->get('nWantedTags');

        $recettes = $doctrine->getRepository(Recette::class)->findAll();

        $scores = [];
        foreach ($recettes as $recette){
            $score = 0;

            $ingredients = $doctrine->getRepository(ComposantRecette::class)->findBy([
                'idRecette' => $recette->getId()
            ]);

            foreach ($ingredients as $ingredient){
                $iScore = 0;

                // add score to ingredient
                if($tags) {
                    foreach ($tags as $tag){
                        $depth = $doctrine->getRepository(AlimentAlimentDepth::class)->findOneBy(['aliment1' => $tag, 'aliment2' => $ingredient->getAliment()]);
                        if(!$depth) continue;
                        $d = $depth->getDepth();

                        if($d > 10) continue;

                        if($d == 0) $iScore += 100;
                        else $iScore += 10 * (10 - $d)/10;
                    }
                }

                // add nscore to ingredient
                if($ntags){
                    foreach ($ntags as $tag){
                        $depth = $doctrine->getRepository(AlimentAlimentDepth::class)->findOneBy(['aliment1' => $tag, 'aliment2' => $ingredient->getAliment()]);
                        if(!$depth) continue;
                        $d = $depth->getDepth();

                        if($d > 10) continue;

                        if($d == 0){
                            $iScore = PHP_INT_MIN;
                            break;
                        }
                        else $iScore -= 10 * (10 - $d)/10;
                    }
                }

                if($iScore == PHP_INT_MIN) {
                    $score = -1;
                    break;
                }
                $score += $iScore;
            }

            if(!$ntags && $score == 0) $score = -1;

            if($score >= 0) $scores[] = ['recette' => $recette, 'score' => $score];
        }

        //sort
        usort($scores, [$this, 'comp']);

        //html
        $html = '';
        foreach ($scores as $score){
            $html .= $this->renderView('recetteItem.html.twig', $score);
        }

        return new JsonResponse([
            'html' => $html,
            'data' => $scores
        ]);
    }

    private function comp($a, $b){
        if($a['score'] === $b['score']) return 0;
        if($a['score'] < $b['score']) return 1;
        return -1;
    }
}