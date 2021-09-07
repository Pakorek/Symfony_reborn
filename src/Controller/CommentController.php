<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
  /**
   * @Route("/", name="index", methods={"GET"})
   * @param CommentRepository $commentRepository
   * @return Response
   */
  public function index(CommentRepository $commentRepository): Response
  {
    return $this->render('comment/index.html.twig', [
      'comments' => $commentRepository->findAll(),
    ]);
  }

  /**
   * @Route("/{slug}/new", name="new", methods={"GET","POST"})
   * @param Request $request
   * @param Episode $episode
   * @return Response
   */
  public function new(Request $request, Episode $episode): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $comment->setUser($user);
      $comment->setEpisode($episode);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($comment);
      $entityManager->flush();

      return $this->redirectToRoute('program_season_show', [
        'slug' => $episode->getSeason()->getProgram()->getSlug(),
        'seasonId' => $episode->getSeason()->getNumber()
        ], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('comment/new.html.twig', [
      'comment' => $comment,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}", name="show", methods={"GET"})
   * @param Comment $comment
   * @return Response
   */
  public function show(Comment $comment): Response
  {
    return $this->render('comment/show.html.twig', [
      'comment' => $comment,
    ]);
  }

  /**
   * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
   * @param Request $request
   * @param Comment $comment
   * @return Response
   */
  public function edit(Request $request, Comment $comment): Response
  {
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('comment/edit.html.twig', [
      'comment' => $comment,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}", name="delete", methods={"POST"})
   * @param Request $request
   * @param Comment $comment
   * @return Response
   */
  public function delete(Request $request, Comment $comment): Response
  {
    if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($comment);
      $entityManager->flush();
    }

    return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
  }
}
