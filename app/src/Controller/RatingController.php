<?php

/**
 * Rating Controller.
 */

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Tape;
use App\Form\RatingType;
use App\Service\RatingService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RatingController.
 *
 * @Route("/rating")
 *
 * @IsGranted("ROLE_USER")
 */
class RatingController extends AbstractController
{
    /**
     * Rating service.
     *
     * @var RatingService
     */
    private $ratingService;

    /**
     * RatingController constructor.
     *
     * @param RatingService $ratingService Rating service
     */
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="rating_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->ratingService->createPaginatedList($page);

        return $this->render(
            'rating/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Rating $rating Rating entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="rating_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Rating $rating): Response
    {
        return $this->render(
            'rating/show.html.twig',
            ['rating' => $rating]
        );
    }

    /**
     * Create action.
     *
     * @param Tape    $tape    Tape entity
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     name="rating_create",
     *     requirements={"id": "[1-9]\d*"},
     *     )
     */
    public function create(Tape $tape, Request $request): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setTape($tape);
            $rating->setAuthor($this->getUser());
            $this->ratingService->save($rating);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('tape_show', ['id' => $tape->getId()]);
        }

        return $this->render(
            'rating/create.html.twig',
            [
                'form' => $form->createView(),
                'tape' => $tape,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Rating  $rating  Rating entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="rating_edit",
     * )
     */
    public function edit(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(RatingType::class, $rating, ['method' => 'PUT']);
        $form->handleRequest($request);
        $tape = $rating->getTape();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ratingService->save($rating);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('tape_show', ['id' => $tape->getId()]);
        }

        return $this->render(
            'rating/create.html.twig',
            [
                'form' => $form->createView(),
                'rating' => $rating,
                'tape' => $tape,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Rating  $rating  Rating entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="rating_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(FormType::class, $rating, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ratingService->delete($rating);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('rating_index');
        }

        return $this->render(
            'rating/delete.html.twig',
            [
                'form' => $form->createView(),
                'rating' => $rating,
            ]
        );
    }
}
