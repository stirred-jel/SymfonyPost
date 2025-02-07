<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_home', methods: ['GET'])]
    public function homepage(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();

        return $this->render('category/home.html.twig', [
            'categoriesList' => $categories,
        ]);
    }

    #[Route('/create', name: 'category_create', methods: ['GET', 'POST'])]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_home');
        }

        return $this->render('category/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[Route('/view/{id}', name: 'category_view', methods: ['GET'])]
    public function viewCategory(Category $category): Response
    {
        return $this->render('category/view.html.twig', [
            'categoryDetail' => $category,
        ]);
    }

    #[Route('/modify/{id}', name: 'category_modify', methods: ['GET', 'POST'])]
    public function updateCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('category_home');
        }

        return $this->render('category/modify.html.twig', [
            'formView' => $form->createView(),
            'categoryDetail' => $category,
        ]);
    }

    #[Route('/remove/{id}', name: 'category_remove', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_home');
    }
}
