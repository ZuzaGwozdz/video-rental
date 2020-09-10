<?php
/**
 * Reservation Controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Tape;
use App\Form\ReservationType;
use App\Service\TapeService;
use App\Service\ReservationService;
use App\Repository\ReservationRepository;
use App\Repository\TapeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReservationController
 *
 * @Route("/reservation")
 * 
 * @IsGranted("ROLE_USER")
 */
class ReservationController extends AbstractController
{
    /**
     * Reservation service.
     *
     * @var ReservationService
     */
    private $reservationService;

    /**
     * Tape service.
     *
     * @var TapeService
     */
    private $tapeService;

    /**
     * ReservationController constructor.
     *
     * @param ReservationService $reservationService Reservation service
     * @param TapeService $tapeService Tape service
     */
    public function __construct(ReservationService $reservationService, TapeService $tapeService)
    {
        $this->reservationService = $reservationService;
        $this->tapeService = $tapeService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     * @param ReservationRepository $reservationRepository Reservation repository
     * @param PaginatorInterface $paginator Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="reservation_index",
     * )
     */

    public function index(Request $request, ReservationRepository $reservationRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $page = $request->query->getInt('page', 1);
            $pagination = $this->reservationService->createPaginatedList($page);
        }
        else
        {
            $page = $request->query->getInt('page', 1);
            $pagination = $this->reservationService->createPaginatedListByAuthor($page, $this->getuser());
        }

        return $this-> render(
            'reservation/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="reservation_show",
     *     requirements={"id": "[1-9]\d*"},
     *     )
     */

    public function show(Reservation $reservation): Response
    {
        return $this -> render(
            'reservation/show.html.twig',
            ['reservation' => $reservation]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Tape $tape Tape entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     name="reservation_create",
     *     requirements={"id": "[1-9]\d*"},
     *     )
     */
    public function create(Request $request, Tape $tape): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setAuthor($this->getUser());
            $reservation->setTape($tape);
            $reservation->setStatus(0);

            $this->reservationService->save($reservation);
            $this->tapeService->save($tape);

            $this->addFlash('sucess', 'message_created_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/create.html.twig',
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
     * @param Reservation $reservation Reservation entity
     * @param ReservationRepository $reservationRepository Reservation repository
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
     *     name="reservation_edit",
     *     )
     */

    public function edit(Request $request, Reservation $reservation): Response
    {
        if ($reservation->getAuthor() !== $this->getUser()) {
           $this->addFlash('warning', 'message.item_not_found');
           return $this->redirectToRoute('reservation_index');
        }

        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/edit.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Reservation $reservation Reservation entity
     * @param ReservationRepository $reservationRepository Reservation repository
     * @param TapeRepository $tapeRepository Tape repository
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
     *     name="reservation_delete",
     *     )
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($reservation->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message_item_not_found');
            return $this->redirectToRoute('reservation_index');
        }

        $form = $this->createForm(FormType::class, $reservation, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tape = $reservation->getTape();
            $tape->setAvailability(1);
            $this->tapeService->save($tape);
            $this->reservationService->delete($reservation);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Confirm action.
     *
     * @param Request $request HTTP request
     * @param Reservation $reservation Reservation entity
     * @param ReservationRepository $reservationRepository Reservation repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/confirm",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="reservation_confirm",
     *     )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function confirm(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, TapeRepository $tapeRepository): Response
    {
        $form = $this->createForm(FormType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($request->isMethod('PUT') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setStatus(1);
            $tape = $reservation->getTape();
            $tape->setAvailability(0);
            $this->tapeService->save($tape);
            $this->reservationService->save($reservation);

            $this->addFlash('success', 'message_confirmed_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/confirm.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}