<?php
/**
 * Tape Controller.
 */

namespace App\Controller;

use App\Entity\Tape;
use App\Form\TapeType;
use App\Repository\TapeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TapeController.
 *
 * @Route("/tape")
 *
 */
class TapeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request $request HTTP request
     * @param TapeRepository $tapeRepository Tape repository
     * @param PaginatorInterface $paginator Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tape_index",
     * )
     */

    public function index(Request $request, TapeRepository $tapeRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tapeRepository->queryAll(),
            $request->query->getInt('page', 1),
            TapeRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this-> render(
            'tape/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Tape $tape Tape entity
     *
     * @return Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="tape_show",
     *     requirements={"id": "[1-9]\d*"},
     *     )
     */

    public function show(Tape $tape): Response
    {
        return $this -> render(
            'tape/show.html.twig',
            ['tape' => $tape]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request        HTTP request
     * @param TapeRepository $tapeRepository Tape repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="tape_create",
     * )
     */
    public function create(Request $request, TapeRepository $tapeRepository): Response
    {
        $tape = new Tape();
        $form = $this->createForm(TapeType::class, $tape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tapeRepository->save($tape);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('tape_index');
        }

        return $this->render(
            'tape/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request        HTTP request
     * @param Tape                         $tape          Tape entity
     * @param TapeRepository            $tapeRepository Tape repository
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
     *     name="tape_edit",
     * )
     */
    public function edit(Request $request, Tape $tape, TapeRepository $tapeRepository): Response
    {
        $form = $this->createForm(TapeType::class, $tape, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tapeRepository->save($tape);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('tape_index');
        }

        return $this->render(
            'tape/edit.html.twig',
            [
                'form' => $form->createView(),
                'tape' => $tape,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request        HTTP request
     * @param Tape                          $tape           Tape entity
     * @param TapeRepository            $tapeRepository Tape repository
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
     *     name="tape_delete",
     * )
     */
    public function delete(Request $request, Tape $tape, TapeRepository $tapeRepository): Response
    {
        $form = $this->createForm(FormType::class, $tape, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tapeRepository->delete($tape);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('tape_index');
        }

        return $this->render(
            'tape/delete.html.twig',
            [
                'form' => $form->createView(),
                'tape' => $tape,
            ]
        );
    }
}