<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StartController.
 * @Route("/")
 */

class StartController extends AbstractController
{
    /**
     * Start action.
     *
     * @return Response HTTP response
     *
     * @Route("/")
     *
     */

    public function start(): Response
    {
        return $this->render(
            'start/index.html.twig',
        );
    }
}
