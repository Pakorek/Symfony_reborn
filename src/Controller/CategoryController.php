<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
  /**
   * @Route("/", name="index")
   * @param CategoryRepository $categoryRepo
   * @return Response
   */
  public function index(CategoryRepository $categoryRepo): Response
  {
    // get all categories -> link to programs related to
    $categories = $categoryRepo->findAll();

    return $this->render('category/index.html.twig', ["categories" => $categories]);
  }

  /**
   * @Route("/new", name="new")
   * @IsGranted("ROLE_ADMIN")
   *
   * @param Request $request
   * @return Response
   */
  public function new(Request $request): Response
  {
    $category = new Category();

    $form = $this->createForm(CategoryType::class, $category);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      //persist and flush
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($category);
      $entityManager->flush();
      // redirect to
      return $this->redirectToRoute('category_index');
    }

    return $this->render('category/new.html.twig', ['form' => $form->createView()]);
  }
}
