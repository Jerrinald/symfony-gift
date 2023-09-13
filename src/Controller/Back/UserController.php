<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/edit-level/{id}', name: 'app_users_edit_level')]
    public function editLevel(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        

        if ($this->isCsrfTokenValid('edit_level'.$user->getId(), $request->request->get('_token'))) {
            $role = $request->request->get('roleVal');
            
            $user->setRoles([$role]); // Notice the use of square brackets to create an array.

            $entityManager->persist($user);
            $entityManager->flush();
        }


        return $this->redirectToRoute('back_app_users', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(array('ROLE_USER'));
            $user->setIsVerified(true);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('back_app_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request,User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_users', [], Response::HTTP_SEE_OTHER);
    }

}
