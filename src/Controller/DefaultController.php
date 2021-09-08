<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function index(): Response
  {
    return $this->render('app/index.html.twig');
  }

  /**
   * @Route("/my-profil", name="user_profil")
   *
   * @return Response
   */
  public function showProfil(): Response
  {
    if (!$this->getUser()) {
      return $this->redirectToRoute("app_register");
    }

    return $this->render("app/user_profil.html.twig");
  }
}
