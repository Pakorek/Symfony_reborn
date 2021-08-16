<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/show/{program<\d+>}", name="show", methods={"GET"})
     *
     * @param Program $program
     * @return Response
     */
    public function show(Program $program): Response
    {
        $nbSeasons = count($program->getSeasons());

        return $this->render('program/show.html.twig', ['program' => $program, "nbSeasons" => $nbSeasons]);
    }

  /**
   * @Route("/show/{program<\d+>}/seasons/{seasonId<\d+>}", name="season_show")
   *
   * @param Program $program
   * @param int $seasonId
   * @param SeasonRepository $seasonRepo
   * @return Response
   */
    public function showSeason(Program $program, int $seasonId, SeasonRepository $seasonRepo)
    {
        $season = $seasonRepo->findOneBy([
            "program_id" => $program->getId(),
            "number" => $seasonId
        ]);

        return $this->render("program/showSeason.html.twig", ["program" => $program, "season" => $season]);
    }
}
