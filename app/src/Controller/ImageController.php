<?php
/**
 * Image controller.
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tape;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImageController.
 *
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * Image repository.
     *
     * @var \App\Repository\ImageRepository
     */
    private $imageRepository;

    /**
     * File uploader.
     *
     * @var \App\Service\FileUploader
     */
    private $fileUploader;

    /**
     * Filesystem component.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * ImageController constructor.
     *
     * @param \App\Repository\ImageRepository          $imageRepository Image repository
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem      Filesystem component
     * @param \App\Service\FileUploader                $fileUploader    File uploader
     */
    public function __construct(ImageRepository $imageRepository, Filesystem $filesystem, FileUploader $fileUploader)
    {
        $this->imageRepository = $imageRepository;
        $this->filesystem = $filesystem;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param Tape                                      $tape    Tape
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     name="image_create",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "POST"}
     * )
     */
    public function create(Request $request, Tape $tape): Response
    {
        if ($tape->getImage()) {
            return $this->redirectToRoute(
                'image_edit',
                ['id' => $tape->getImage()->getId()]
            );
        }
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFilename = $this->fileUploader->upload(
                $form->get('file')->getData()
            );
            $image->setTape($tape);
            $image->setFilename($imageFilename);
            $this->imageRepository->save($image);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('task_index');
        }

        return $this->render(
            'image/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Image                         $image   Image
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     name="image_edit",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Image $image): Response
    {
        if (!$image->getTape()) {
            return $this->redirectToRoute('image_create');
        }
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filesystem->remove(
                $this->getParameter('images_directory').'/'.$image->getFilename()
            );
            $imageFilename = $this->fileUploader->upload(
                $form->get('file')->getData()
            );
            $image->setFilename($imageFilename);
            $this->imageRepository->save($image);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('tape_index');
        }

        return $this->render(
            'image/edit.html.twig',
            [
                'form' => $form->createView(),
                'image' => $image,
            ]
        );
    }
}
