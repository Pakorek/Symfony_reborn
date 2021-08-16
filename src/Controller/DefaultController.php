<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/show/{id<\d+>}", name="show", methods={"GET"})
     *
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        // get program $id in db

        return $this->render('app/show.html.twig', ['id' => $id]);
    }
}
