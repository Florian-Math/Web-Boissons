<?php


namespace App\Controller;

use App\Entity\AlimentAlimentDepth;
use App\Entity\ComposantRecette;
use App\Entity\Hierarchie;
use App\Entity\Recette;
use App\Entity\SousCategorie;
use App\Entity\SuperCategorie;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response{

        $aliments = $doctrine->getRepository(Hierarchie::class)->findSousCategories('Aliment');

        return $this->render('home.html.twig', [
            'aliments' => $aliments
        ]);
    }

    /**
     * @Route("/Aliment/{path}", name="discover", requirements={"path"=".+"})
     */
    public function discover(Request $request, ManagerRegistry $doctrine, $path = ''): Response{

        $aliments = explode('/', $path);
        $sousCategories = $doctrine->getRepository(Hierarchie::class)->findSousCategories('Aliment');

        $recettes = $doctrine->getRepository(Recette::class)->findByIngredient(end($aliments));

        if(empty($path)){
            return $this->render('discover.html.twig', [
                'aliments' => $sousCategories,
                'recettes' => $recettes
            ]);
        }

        foreach ($aliments as $aliment){
            $ok = false;
            foreach ($sousCategories as $sous){
                if($sous->getNomAliment() == $aliment){
                    $ok = true;
                }
            }
            if($ok) $sousCategories = $doctrine->getRepository(Hierarchie::class)->findSousCategories($aliment);
            else throw $this->createNotFoundException('Aliment not found.' . $path);
        }

        return $this->render('discover.html.twig', [
            'paths' => $aliments,
            'aliments' => $sousCategories,
            'recettes' => $recettes
        ]);
    }

    /**
     * @Route("/import/AD85FC347812422E", name="import")
     */
    public function import(Request $request, ManagerRegistry $doctrine): Response{
        require_once __DIR__ . '../../../Donnees.inc.php';
        $manager = $doctrine->getManager();

        // delete all data
        $recettes = $doctrine->getRepository(Recette::class)->findAll();
        foreach($recettes as $r){
            $manager->remove($r);
        }

        $composants = $doctrine->getRepository(ComposantRecette::class)->findAll();
        foreach($composants as $c){
            $manager->remove($c);
        }

        $hierarchies = $doctrine->getRepository(Hierarchie::class)->findAll();
        foreach($hierarchies as $h){
            $manager->remove($h);
        }

        $sousCategories = $doctrine->getRepository(SousCategorie::class)->findAll();
        foreach($sousCategories as $s){
            $manager->remove($s);
        }

        $superCategories = $doctrine->getRepository(SuperCategorie::class)->findAll();
        foreach($superCategories as $s){
            $manager->remove($s);
        }

        $a_a_depths = $doctrine->getRepository(AlimentAlimentDepth::class)->findAll();
        foreach($a_a_depths as $d){
            $manager->remove($d);
        }

        $manager->flush();

        // insert all

        foreach ($Recettes as $val){
            $recette = (new Recette())
                ->setTitre($val['titre'])
                ->setIngredients($val['ingredients'])
                ->setPreparation($val['preparation']);

            $manager->persist($recette);
            $manager->flush();

            foreach ($val['index'] as $aliment) {
                if($doctrine->getRepository(ComposantRecette::class)->findBy(['idRecette' => $recette->getId(), 'aliment' => $aliment])) continue;

                $a = (new ComposantRecette())
                    ->setIdRecette($recette->getId())
                    ->setAliment($aliment);
                $manager->persist($a);
                $manager->flush();
            }
        }

        // parcours de $Herarchie
        foreach ($Hierarchie as $aliment => $val){
            $hierarchie = (new Hierarchie())
                ->setNomAliment($aliment);
            $manager->persist($hierarchie);
            $manager->flush();

            if(isset($val['super-categorie'])){
                foreach ($val['super-categorie'] as $super) {
                    $superCategorie = (new SuperCategorie())
                        ->setAliment($aliment)
                        ->setSuperCategorie($super);
                    $manager->persist($superCategorie);
                }
                $manager->flush();
            }
            if(isset($val['sous-categorie'])){
                foreach ($val['sous-categorie'] as $sous) {
                    $sousCategorie = (new SousCategorie())
                        ->setAliment($aliment)
                        ->setSousCategorie($sous);
                    $manager->persist($sousCategorie);
                }
                $manager->flush();
            }
        }

        $depths = $this->alimentToAlimentScores($doctrine);
        foreach ($depths as $alim1 => $val){
            foreach ($val as $alim2 => $depth){
                $d = (new AlimentAlimentDepth())
                    ->setAliment1($alim1)
                    ->setAliment2($alim2)
                    ->setDepth($depth);
                $doctrine->getManager()->persist($d);
            }
        }

        $doctrine->getManager()->flush();

        return $this->redirectToRoute('home');
    }

    // ----

    public function alimentToAlimentScores(ManagerRegistry $doctrine){
        $depths = [];
        $aliments = $doctrine->getRepository(Hierarchie::class)->findAll();

        foreach ($aliments as $aliment){
            $a1 = $aliment->getNomAliment();

            $depths[$a1][$a1] = 0;
            $depths[$a1] = $this->subCategoryScores($doctrine, $a1, $depths[$a1]);
        }

        //return new Response("<pre>". print_r($depths, true) . "</pre>");
        return $depths;
    }

    function subCategoryScores(ManagerRegistry $doctrine, $root, $depths = [], $depth = 0){
        $sousCategories = $doctrine->getRepository(Hierarchie::class)->findSousCategories($root);

        foreach ($sousCategories as $sousCategory){
            $a2 = $sousCategory->getNomAliment();

            if(isset($depths[$a2]) && $depths[$a2] <= $depth + 1) continue;

            $depths[$a2] = $depth + 1;

            $newDepths = $this->subCategoryScores($doctrine, $a2, $depths, $depth+1);

            foreach ($newDepths as $key => $val){
                if(!isset($depths[$key]) || $depths[$key] > $val) $depths[$key] = $val;
            }
        }

        return $depths;
    }


}