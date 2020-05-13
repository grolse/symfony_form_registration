<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Form\UserRegisterType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param $userId
     * @param UserServiceInterface $userService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/detail/{userId}", name="user_detail")
     */
    public function index(int $userId, UserServiceInterface $userService)
    {
        $user = $userService->getUserById($userId);

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param UserServiceInterface $userService
     *
     * @Route("/user/register", name="user_register")
     */
    public function register(Request $request, UserServiceInterface $userService)
    {
        $form = $this->createForm(UserRegisterType::class, new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $userService->create($form->getData());
            } catch (UserAlreadyExistsException $exception) {
                return $this->render('user/register.html.twig', [
                    'form' => $form->createView(),
                    'errors' => [
                        $exception->getMessage()
                    ]
                ]);
            }
            return $this->redirectToRoute('user_detail', [
                'userId' => $user->getId()
            ]);
        }
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
