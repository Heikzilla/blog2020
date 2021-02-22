<?php
namespace AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Article;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);

    }

    /**
     * @Route("/userpage", name="userpage")
     */
    public function userPage(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        #var_dump($user);
        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository(Article::class)->findBy(array('user' => $user->getId()));
        #var_dump($articles);

        if (!$articles) {
            $articles = false;
        }

        foreach($articles as $article){
            $short = substr($article->getText(),0 ,384 );
            $article->setText($short . "...");
        }
        
        return $this->render('default/userpage.html.twig', [
            'articles' => $articles,
            'user' => $user,
        ]);
    }

}