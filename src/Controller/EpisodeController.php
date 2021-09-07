<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/episode", name="episode_")
 */
class EpisodeController extends AbstractController
{
  /**
   * @Route("/", name="index", methods={"GET"})
   * @param EpisodeRepository $episodeRepository
   * @return Response
   */
  public function index(EpisodeRepository $episodeRepository): Response
  {
    return $this->render('episode/index.html.twig', [
      'episodes' => $episodeRepository->findAll(),
    ]);
  }

  /**
   * @Route("/new", name="new", methods={"GET","POST"})
   * @param Request $request
   * @param Slugify $slugify
   * @return Response
   */
  public function new(Request $request, Slugify $slugify): Response
  {
    $episode = new Episode();
    $form = $this->createForm(EpisodeType::class, $episode);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $episode->setSlug((string)$episode->getNumber() . "-" . $slugify->generate($episode->getTitle()));
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($episode);
      $entityManager->flush();

      return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('episode/new.html.twig', [
      'episode' => $episode,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{slug}", name="show", methods={"GET"})
   * @param Episode $episode
   * @return Response
   */
  public function show(Episode $episode): Response
  {
    return $this->render('episode/show.html.twig', [
      'episode' => $episode,
    ]);
  }

  /**
   * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
   * @param Request $request
   * @param Episode $episode
   * @return Response
   */
  public function edit(Request $request, Episode $episode): Response
  {
    $form = $this->createForm(EpisodeType::class, $episode);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('episode/edit.html.twig', [
      'episode' => $episode,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}", name="delete", methods={"POST"})
   * @param Request $request
   * @param Episode $episode
   * @return Response
   */
  public function delete(Request $request, Episode $episode): Response
  {
    if ($this->isCsrfTokenValid('delete' . $episode->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($episode);
      $entityManager->flush();
    }

    return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
  }
}
