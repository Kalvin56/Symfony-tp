<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/category", name="category.index")
     */
    public function index(Request $request): Response
    {
        $categories = $this->repository->findAll();
        $numberArticles = [];
        foreach ($categories as $cat) {
            $nb = count($cat->getArticles()->toArray());
            array_push($numberArticles, $nb);
        };
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'numberArticles' => $numberArticles
        ]);
    }

    /**
     * @Route("/category/{id}", name="category.show")
     */
    public function show(PaginatorInterface $paginator, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $category = $this->repository->find($id);
        $articles = $category->getArticles()->toArray();
        $numberArticles = count($articles);
        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            1
        );
        if (!$category)
        {
            throw $this->createNotFoundException('The category does not exist');
        }
        return $this->render('category/show.html.twig', [
            'categoryName' => $category->getName(),
            'articles' => $pagination,
            'numberArticles' => $numberArticles
        ]);
    }
}
