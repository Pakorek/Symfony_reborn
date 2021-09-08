<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\User;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
  private $programRepo;

  public function __construct(ProgramRepository $programRepo)
  {
    $this->programRepo = $programRepo;
  }

  /**
   * Show all rows from Program's entity
   *
   * @Route("/", name="index")
   * @return Response
   */
  public function index(): Response
  {
    $programs = $this->programRepo->findAll();

    if (!$programs) {
      throw $this->createNotFoundException(
        'No program found :/'
      );
    }

    return $this->render('program/index.html.twig', [
      'website' => 'Wild Series',
      'programs' => $programs
    ]);
  }

  /**
   * @Route("/new", name="new")
   *
   * @param Request $request
   * @param Slugify $slugify
   * @param MailerInterface $mailer
   * @return Response
   * @throws TransportExceptionInterface
   */
  public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $program = new Program();

    $form = $this->createForm(ProgramType::class, $program);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $program->setSlug($slugify->generate($program->getTitle()));
      $program->setOwner($user);
      $em = $this->getDoctrine()->getManager();
      $em->persist($program);
      $em->flush();

      $email = (new Email())
        ->from($this->getParameter('mailer_from'))
        ->to($this->getParameter('mailer_to'))
        ->subject('New program added !')
        ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]))
        ;

      $mailer->send($email);

      return $this->redirectToRoute("program_index");
    }

    return $this->render('program/new.html.twig', ['form' => $form->createView()]);
  }

  /**
   * @Route("/show/{slug}", name="show", methods={"GET"})
   *
   * @param Program $program
   * @return Response
   */
  public function show(Program $program): Response
  {
    $seasons = $program->getSeasons();

    $actors = $program->getActors();

    return $this->render('program/show.html.twig', [
      'program' => $program,
      'seasons' => $seasons,
      'actors' => $actors
    ]);
  }

  /**
   * @Route("/show/{slug}/seasons/{seasonId<\d+>}", name="season_show")
   *
   * @param Program $program
   * @param int $seasonId
   * @param SeasonRepository $seasonRepo
   * @return Response
   */
  public function showSeason(Program $program, int $seasonId, SeasonRepository $seasonRepo)
  {
    $season = $seasonRepo->findOneBy([
      "program" => $program,
      "number" => $seasonId
    ]);

    return $this->render("program/showSeason.html.twig", ["program" => $program, "season" => $season]);
  }

  /**
   * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
   *
   * @param Request $request
   * @param Program $program
   * @return Response
   */
  public function edit(Request $request, Program $program): Response
  {
    if (!($this->getUser() == $program->getOwner())) {
      throw new AccessDeniedException('You\'r not allowed to edit this program !');
    }

    $form = $this->createForm(ProgramType::class, $program);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('program/edit.html.twig', [
      'program' => $program,
      'form' => $form,
    ]);
  }

}
