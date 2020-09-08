<?php
/**
 * Tape Controller.
 */

namespace App\Controller;

use App\Entity\Tape;
use App\Form\TapeType;
use App\Repository\TapeRepository;
use App\Service\TapeService;
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
 * Class TapeController.
 *
 * @Route("/")
 *
 */
class TapeController extends AbstractController
{
    /**
     * Tape service.
     *
     * @var TapeService
     */
    private $tapeService;

    /**
     * TapeController constructor.
     *
     * @param TapeService $tapeService Tape service
     */
    public function __construct(TapeService $tapeService)
    {
        $this->tapeService = $tapeService;
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
     *     name="tape_index",
     * )
     */

    public function index(Request $request): Response
    {
        $pagination = $this->tapeService->createPaginatedList(
            $request->query->getInt('page', 1),
            $request->query->getAlnum('filters', [])
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
     *
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
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $tape = new Tape();
        $form = $this->createForm(TapeType::class, $tape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tapeService->save($tape);
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
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tape $tape): Response
    {
        $form = $this->createForm(TapeType::class, $tape, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tapeService->save($tape);
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
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tape $tape): Response
    {
        $form = $this->createForm(FormType::class, $tape, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tapeService->delete($tape);
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