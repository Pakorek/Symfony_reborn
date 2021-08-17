<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
  /**
   * @Route("/", name="index")
   * @param ActorRepository $actorRepo
   * @return Response
   */
  public function index(ActorRepository $actorRepo): Response
  {
    return $this->render('actor/index.html.twig', [
      'actors' => $actorRepo->findAll()
    ]);
  }

  /**
   * @Route("/show/{actor}", name="show")
   *
   * @param Actor $actor
   * @return Response
   */
  public function show(Actor $actor): Response
  {
    return $this->render("actor/show.html.twig", ['actor' => $actor]);
  }
}
