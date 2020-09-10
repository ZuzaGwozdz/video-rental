<?php

/**
 * Account Controller.
 */
namespace App\Controller;

use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ChangePasswordFormType;

/**
 * Class AccountController.
 *
 * @Route("/account")
 *
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * User service.
     *
     * @var UserService
     */
    private $userService;

    /**
     * Security helper.
     * 
     * @var Security
     */
    private $security;

    /**
     * AccountController constructor.
     *
     * @param Security $security Security helper
     * @param UserService $userService User service
     */
    public function __construct(Security $security, UserService $userService)
    {
       $this->security = $security;
       $this->userService = $userService;
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
     *     name="account_index",
     * )
     */

    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render(
            'account/index.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request  HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/edit",
     *     methods={"GET", "PUT"},
     *     name="account_edit",
     * )
     */
    public function edit(Request $request): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('account_index');
        }

        return $this->render(
            'account/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

     /**
     * Reset password action.
     *
     * @param Request $request  HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/reset",
     *     methods={"GET", "POST"},
     *     name="account_reset",
     * )
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);

        $this->addFlash('success', 'password_changed_successfully');
    }

}
