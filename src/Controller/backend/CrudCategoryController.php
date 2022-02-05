<?php

namespace App\Controller\backend;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/category")
 */
class CrudCategoryController extends AbstractController
{
    /**
     * @Route("/", name="crud_category_index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('backend/pages/crud_category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_category_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('crud_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_category_show", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('backend/pages/crud_category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_category_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_category_delete", methods={"POST"})
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
