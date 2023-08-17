<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users-manage')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/users.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

}
