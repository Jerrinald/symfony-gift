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
use App\Entity\ListGift;
use App\Form\EditListGiftType;
use App\Form\ListGiftType;
use App\Repository\ListGiftRepository;


#[Route('/list-manage')]
class ListGiftController extends AbstractController
{
    #[Route('/', name: 'app_list_gift_index')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $session = $request->getSession();

        if ($session->has('selected_user_id_' . $user->getId())) {
            $selectedUserId = $session->get('selected_user_id_' . $user->getId());
            $userSelected = $userRepository->find($selectedUserId);
        }

        if ($request->isMethod('POST')) {
            $selectedUserId = $request->request->get('selectedUserId');
            $userSelected = $userRepository->find($selectedUserId);

            if (!$userSelected) {
                return $this->render('back/list_gift/lists.html.twig', [
                    'users' => $userRepository->findAll()
                ]);
            }

            //$userLists = $listGiftRepository->findBy(['userList' => $user]);

            // Store the selected user's ID in a session variable
            $session->set('selected_user_id_' . $user->getId(), $selectedUserId);

        }

        if (isset($userSelected)) {
            $list_gifts = $userSelected->getListGifts();

            return $this->render('back/list_gift/lists.html.twig', [
                'userSelec' => $userSelected,
                'users' => $userRepository->findAll(),
                'list_gifts' => $list_gifts,
            ]);
        }

        return $this->render('back/list_gift/lists.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/new/{id}', name: 'app_list_gift_new', methods: ['GET', 'POST'])]
    public function new(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $listGift = new ListGift();
        $form = $this->createForm(ListGiftType::class, $listGift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $password = $form->get('password')->getData();

            // Hashing a password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $listGift->setPassword($hashedPassword);

            $listGift->setUserList($user);
            $entityManager->persist($listGift);
            $entityManager->flush();

            return $this->redirectToRoute('back_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/list_gift/new.html.twig', [
            'list_gift' => $listGift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_gift_show', methods: ['GET'])]
    public function show(ListGift $listGift): Response
    {
        return $this->render('back/list_gift/show.html.twig', [
            'list_gift' => $listGift,
        ]);
        
    }

    #[Route('/{id}/edit', name: 'app_list_gift_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        $passStored = $listGift->getPassword();
        $form = $this->createForm(EditListGiftType::class, $listGift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            
            if ($password != null) {

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $listGift->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('back_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/list_gift/edit.html.twig', [
            'list_gift' => $listGift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_gift_delete', methods: ['POST'])]
    public function delete(Request $request, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listGift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($listGift);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('archive/{id}', name: 'app_list_gift_archive', methods: ['POST'])]
    public function archive(Request $request, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('archive'.$listGift->getId(), $request->request->get('_token'))) {
            if ($listGift->isActive()) {
                $listGift->setActive(false);
            }else {
                $listGift->setActive(true);
            }
            
            $entityManager->persist($listGift);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
    }

}
