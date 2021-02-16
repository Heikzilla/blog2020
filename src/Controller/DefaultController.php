<?php

namespace App\Controller;

use Entity\Article;
use Entity\Task;
use Entity\User;
use Form\UserType;
use Doctrine\DBAL\Types\Type;
use Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
use Symfony\Component\Mime\Message;
use Symfony\Component\Security\Core\User\User as UserUser;

class DefaultController extends AbstractController
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
        $article->setUser($this->get('security.token_storage')->getToken()->getUser());
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return new Response('Article added successfuly');
        }

        return $this->render('blog/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/updateArticle/{articleID}")
     */
    public function updateArticle(Request $request, int $articleID)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($articleID);

        if($this->get('security.token_storage')->getToken()->getUser() !== $article->getUser()){
            return $this->redirect('/blog/list/' . $articleID);
        }

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$articleID
            );
        }

        $article->setTitle($article->getTitle());
        $article->setText($article->getText());

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, ['label'=> 'Update Article'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager->flush();
            return new Response('Article Updated successfuly');
        }

        return $this->render('blog/update.html.twig',[
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/blog/deleteArticle/{articleID}", name="article_delete")
     * 
     */
    public function deleteArticle(Request $request, int $articleID)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($articleID);

        if($this->get('security.token_storage')->getToken()->getUser() !== $article->getUser()){
            return $this->redirect('/userpage');
        }

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$articleID
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();


        $this->addFlash('Success', 'Post has been removed');
        return $this->redirect('userpage');

    }

    /**
     * @Route("/blog/list")
     */
    public function showAllArticle()
    {

        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository(Article::class)->findAll();

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
    public function showArticle(int $articleID)
    {
       $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($articleID);

        if (!$article) {
            throw $this->createNotFoundException(
                'No Article found for ID: ' . $articleID
            );
        }

        #var_dump(!$article->getIsPublic());

        return $this->render('blog/article.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/blog/visibleArticle/{articleID}", name="visible")
     */
    public function visibleArticle(Request $request, int $articleID)
    {
        var_dump($articleID);

        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($articleID);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $articleID
            );
        }
        $article->setIsPublic(!$article->getIsPublic());

        $entityManager->flush();

        new Response('Article status changed to ' . $article->getIsPublic());
        
        return $this->redirect('/userpage');
    }


}