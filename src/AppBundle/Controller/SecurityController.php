<?php
namespace AppBundle\Controller;

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

    public function changePasswort(){

    }


    /**
     * @Route("/userpage", name="userpage")
     */
    public function userPage(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('default/userpage.html.twig', [
            
            'user' => $user,
        ]);
    }

}