<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article.index")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('article/index.html.twig', [
            'articles' => $pagination,
        ]);
    }

    /**
     * @Route("/article/new", name="article.create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $article->setCreatedAt(new \DateTime);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article.show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/create.html.twig', [
            'articleForm' => $articleForm->createView() 
        ]);
    }

    /**
     * @Route("/article/{id}/edit", name="article.edit")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $id = $request->attributes->get('id');
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);

        if (!$article)
        {
            throw $this->createNotFoundException('The article does not exist');
        }

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article.show', [
                'id' => $article->getId()
            ]);
        }
        
        return $this->render('article/update.html.twig', [
            'articleForm' => $articleForm->createView() 
        ]);
    }

    /**
     * @Route("/article/{id}", name="article.show")
     */
    public function show(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);
        if (!$article)
        {
            throw $this->createNotFoundException('The article does not exist');
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/{id}/delete", name="article.delete")
     */
    public function delete(Request $request, EntityManagerInterface $em): Response
    {
        $id = $request->attributes->get('id');
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);
        if (!$article)
        {
            throw $this->createNotFoundException('The article does not exist');
        }
        $csrfToken = $_GET['token'];
        if ($this->isCsrfTokenValid('delete-item', $csrfToken))
        {
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', "L'article {$article->getTitle()} a bien été supprimé !");
            
            return $this->redirectToRoute('article.index');
        }else{
            throw new InvalidCsrfTokenException();
        }
        
    }

    /**
     * @Route("/article/{id}/like", name="article.delete")
     */
    public function like(Request $request, EntityManagerInterface $em): Response
    {
        $id = $request->attributes->get('id');
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);
        if (!$article)
        {
            return $this->json('The article does not exist', 404);
        }
        $article->incrementLikes();
        $em->persist($article);
        $em->flush();
        return $this->json(['likes' => $article->getLikes()], 200);
        
    }
}
