<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, ManagerRegistry $doctrine): Response
    {
        $error = "";

        // check form
        if($request->isMethod('POST')){
            $user = $doctrine->getRepository(User::class)->findOneBy([
                'login' => $request->get('_login'),
                'mdp' => $request->get('_mdp')
            ]);

            // if login
            if ($user){
                // add favorites
                $oldFavs = (isset($_COOKIE['favorites'])) ? json_decode($_COOKIE['favorites']) : [];
                $newFavs = ($user->getFavorites()) ? $user->getFavorites() : [];

                // merge favs
                foreach ($oldFavs as $fav){
                    if(!in_array($fav, $newFavs)) $newFavs[] = $fav;
                }
                // set favs to user
                $user->setFavorites($newFavs);
                $doctrine->getManager()->flush();
                setcookie("favorites", "", time() - 3600);

                // set user in session
                $request->getSession()->set('user', $user);

                // redirect
                return $this->redirectToRoute('home');
            }else{
                $error = "Informations erronées";
            }
        }

        return $this->render('user/login.html.twig', [
            'login' => $request->get('_login'),
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, ManagerRegistry $doctrine): Response
    {
        $error = "";

        $user = new User();



        // set form info to user
        if($request->get('_login')) $user->setLogin($request->get('_login'));
        if($request->get('_mdp')) $user->setMdp($request->get('_mdp'));
        if($request->get('_nom')) $user->setNom($request->get('_nom'));
        if($request->get('_prenom')) $user->setPrenom($request->get('_prenom'));
        if($request->get('_sexe')) $user->setSexe($request->get('_sexe'));
        if($request->get('_email')) $user->setEmail($request->get('_email'));
        if($request->get('_naissance')) $user->setNaissance(\DateTime::createFromFormat('d/m/Y', $request->get('_naissance')));
        if($request->get('_adresse1') && $request->get('_adresse2') && $request->get('_adresse3')) $user->setAdresse($request->get('_adresse1').'|'.$request->get('_adresse2').'|'.$request->get('_adresse3'));
        if($request->get('_telephone')) $user->setTelephone($request->get('_telephone'));

        // set favorites to user
        $favs = (isset($_COOKIE['favorites'])) ? json_decode($_COOKIE['favorites']) : [];
        $user->setFavorites($favs);

        // check form
        if($request->isMethod('POST')){
            $checkUser = $doctrine->getRepository(User::class)->findOneBy([
                'login' => $request->get('_login')
            ]);

            // if login don't exists
            if(!$checkUser) {
                // save user
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // clear cookies
                setcookie("favorites", "", time() - 3600);

                // set user in session
                $request->getSession()->set('user', $user);

                // redirect
                return $this->redirectToRoute('home');
            }
            else $error = "Login déjà utilisé";
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'error' => $error
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil(Request $request): Response
    {
        return $this->render('user/profil.html.twig', [
            'user' => $request->getSession()->get('user')
        ]);
    }

    /**
     * @Route("/modifProfil", name="modifProfil")
     */
    public function modifProfil(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy([
            'id' => $request->getSession()->get('user')->getId()
        ]);

        if($request->isMethod('POST') && $user != null){
            $checkUser = $doctrine->getRepository(User::class)->findOneBy([
                'login' => $request->get('_login')
            ]);

            if($checkUser && $user->getId() != $checkUser->getId()) {
                return $this->render('user/modifProfil.html.twig', [
                    'user' => $user,
                    'error' => 'Le login existe déjà'
                ]);
            }

            if($request->get('_login')) $user->setLogin($request->get('_login'));
            if($request->get('_mdp')) $user->setMdp($request->get('_mdp'));
            if($request->get('_nom')) $user->setNom($request->get('_nom'));
            else $user->setNom('');
            if($request->get('_prenom')) $user->setPrenom($request->get('_prenom'));
            else $user->setPrenom('');
            if($request->get('_sexe')) $user->setSexe($request->get('_sexe'));
            else $user->setSexe('');
            if($request->get('_email')) $user->setEmail($request->get('_email'));
            else $user->setEmail('');
            if($request->get('_naissance')) $user->setNaissance(\DateTime::createFromFormat('d/m/Y', $request->get('_naissance')));
            if($request->get('_adresse1') && $request->get('_adresse2') && $request->get('_adresse3')) $user->setAdresse($request->get('_adresse1').'|'.$request->get('_adresse2').'|'.$request->get('_adresse3'));
            else $user->setAdresse('');
            if($request->get('_telephone')) $user->setTelephone($request->get('_telephone'));
            else $user->setTelephone('');

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $request->getSession()->set('user', $user);

            return $this->redirectToRoute('profil');
        }



        return $this->render('user/modifProfil.html.twig', [
            'user' => $user,
            'error' => ''
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request): Response
    {
        $request->getSession()->remove('user');

        return $this->redirectToRoute('home');
    }
}