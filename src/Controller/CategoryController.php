<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category.index")
     */
    public function index(Request $request): Response
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        foreach ($categories as $key => $cat) {
            $nb = count($cat->getArticles()->toArray());
            echo $nb;
        };
        // $cat = $categories[0];
        // dd( );
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/{id}", name="category.show")
     */
    public function show(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($id);
        if (!$category)
        {
            throw $this->createNotFoundException('The category does not exist');
        }
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
