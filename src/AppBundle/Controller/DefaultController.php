<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\User as UserUser;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/blog/createArticle")
     */
    public function createArticle(Request $request)
    {
        
        $article = new Article();
        $article->setTitle('write the Title');
        $article->setText('write the Article');
        $article->setUser($this->get('security.token_storage')->getToken()->getUser()->getId());
        $article->setDueTime(new \DateTime("now"));

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            ->add('isPublic', ChoiceType::class, [
                'choices'  => [
                    'Ja' => true,
                    'Nein' => false
                ]
            ])
            ->add('save', SubmitType::class, ['label'=> 'Create Article'])
            ->getForm();

        $form->handleRequest($request);    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return new Response('Article added successfuly');
        }

        return $this->render('blog/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    public function updateArticle(){

    }

    /**
     * @Route("/blog/list")
     */
    public function showAllArticle()
    {

        $articles = $this->getDoctrine()
        ->getRepository(Article::class)
        ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No Article list has been found'
            );
        }

        return $this->render('blog/articles.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    /**
     * @Route("/blog/list/{articleID}")
     */
    public function showArticle($articleID)
    {
      
        $article = $this->getDoctrine()
        ->getRepository(Article::class)
        ->find($articleID);

        if (!$article) {
            throw $this->createNotFoundException(
                'No Article found for ID: ' . $articleID
            );
        }

        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ]);
    }
}
