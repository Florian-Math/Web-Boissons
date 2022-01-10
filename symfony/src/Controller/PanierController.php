<?php
namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response{
        $favs = $this->getFavorites($request);

        $recettes = [];
        foreach ($favs as $fav){
            $r = $doctrine->getRepository(Recette::class)->find($fav);
            if($r) $recettes[] = $r;
        }

        return $this->render('panier.html.twig',[
            'recettes' => $recettes
        ]);
    }

    /**
     * @Route("/Ajax/addFavorite", name="addFavorite")
     */
    public function addFavorite(Request $request, ManagerRegistry $doctrine){
        // get fav
        $favs = $this->getFavorites($request);

        // add id to favorites
        if(!in_array($request->get('id'), $favs)) $favs[] = $request->get('id');

        // set fav
        $this->setFavoritesToUser($request, $doctrine, $favs);

        return new JsonResponse();
    }

    /**
     * @Route("/Ajax/removeFavorite", name="removeFavorite")
     */
    public function removeFavorite(Request $request, ManagerRegistry $doctrine){
        // get fav
        $favs = $this->getFavorites($request);

        // remove id to favorites
        if(in_array($request->get('id'), $favs)) array_splice($favs, array_search($request->get('id'), $favs), 1);

        // set fav
        $this->setFavoritesToUser($request, $doctrine, $favs);

        return new JsonResponse();
    }

    public function getFavorites(Request $request){
        if($request->getSession()->get('user')) return $request->getSession()->get('user')->getFavorites();
        else return isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites']) : [];
    }

    public function setFavoritesToUser(Request $request, ManagerRegistry $doctrine, $favorites){
        if($request->getSession()->get('user')) {
            $user = $doctrine->getRepository(User::class)->find($request->getSession()->get('user')->getId());
            $user->setFavorites($favorites);

            $doctrine->getManager()->flush();
            $request->getSession()->set('user', $user);
        }
        else setcookie('favorites', json_encode($favorites), time()+3600*24*7, '/');
    }
}
?>
