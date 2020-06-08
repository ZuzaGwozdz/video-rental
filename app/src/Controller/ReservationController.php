<?php
/**
 * Reservation Controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReservationController
 *
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{/**
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
        $pagination = $paginator->paginate(
            $reservationRepository->queryByAuthor($this->getUser()),
            $request->query->getInt('page', 1),
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );

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
        if ($reservation->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('reservation_index');
        }

        return $this -> render(
            'reservation/show.html.twig',
            ['reservation' => $reservation]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param ReservationRepository $reservationRepository Reservation repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="reservation_create",
     *     )
     */
    public function create(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setAuthor($this->getUser());
            $reservationRepository->save($reservation);

            $this->addFlash('sucess', 'message_created_successfully');

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render(
            'reservation/create.html.twig',
            ['form' => $form->createView()]
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

    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($reservation->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('reservation_index');
        }

        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation);

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
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($reservation->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('reservation_index');
        }
        
        $form = $this->createForm(FormType::class, $reservation, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->delete($reservation);
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
}