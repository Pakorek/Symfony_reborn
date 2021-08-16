<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/", name="index")
     * @param ProgramRepository $programRepo
     * @return Response
     */
    public function index(ProgramRepository $programRepo): Response
    {
        $programs = $programRepo->findAll();

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
     * @Route("/show/{id<\d+>}", name="show", methods={"GET"})
     *
     * @param int $id
     * @param ProgramRepository $programRepo
     * @return Response
     */
    public function show(int $id, ProgramRepository $programRepo): Response
    {
        $program = $programRepo->find($id);

        return $this->render('program/show.html.twig', ['program' => $program]);
    }
}
