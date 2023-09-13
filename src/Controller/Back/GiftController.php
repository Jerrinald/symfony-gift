<?php

namespace App\Controller\Back;

use App\Entity\Gift;
use App\Entity\ListGift;
use App\Form\GiftType;
use App\Repository\GiftRepository;
use App\Repository\ListGiftRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gift-manage')]
class GiftController extends AbstractController
{
    #[Route('/', name: 'app_gift_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ListGiftRepository $listGiftRepository, GiftRepository $giftRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $session = $request->getSession();

        $gifts = [];

        if ($session->has('selected_gift_list_id_' . $user->getId())) {
            $selectedUserId = $session->get('selected_gift_user_id_' . $user->getId());
            $userSelected = $userRepository->find($selectedUserId);

            $selectedListId = $session->get('selected_gift_list_id_' . $user->getId());
            $listSelected = $listGiftRepository->find($selectedListId);

            $list_gifts = $userSelected->getListGifts();

            $gifts = $listSelected->getGift();
            
        }

        if ($request->isMethod('POST')) {
            if ($request->request->has('userForm')) {
                // User form was submitted
                $selectedUserId = $request->request->get('selectedUserId');
                // Handle user form submission
                $userSelected = $userRepository->find($selectedUserId);

                if (!$userSelected) {
                    return $this->render('back/gift/gifts.html.twig', [
                        'users' => $userRepository->findAll()
                    ]);
                }

                $list_gifts = $userSelected->getListGifts();

                $gifts = [];

                // Store the selected user's ID in a session variable
                $session->set('selected_gift_user_id_' . $user->getId(), $selectedUserId);
    
            } elseif ($request->request->has('listForm')) {

                $selectedUserId = $session->get('selected_gift_user_id_' . $user->getId());
                $userSelected = $userRepository->find($selectedUserId);
                $list_gifts = $userSelected->getListGifts();

                // List form was submitted
                $selectedListId = $request->request->get('selectedListId');
                // Handle user form submission
                $listSelected = $listGiftRepository->find($selectedListId);

                $gifts = $listSelected->getGift();

                $session->set('selected_gift_list_id_' . $user->getId(), $selectedListId);

            }
        }

        if (isset($list_gifts)) {
            if (!isset($listSelected)) {
                $listSelected  = [];
            }
            return $this->render('back/gift/gifts.html.twig', [
                'userSelec' => $userSelected,
                'users' => $userRepository->findAll(),
                'gifts' => $gifts,
                'list_gifts' => $list_gifts,
                'listSelected' => $listSelected,
            ]);
        }

        return $this->render('back/gift/gifts.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/new/{id}', name: 'app_gift_new', methods: ['GET', 'POST'])]
    public function new(ListGift $listGift, Request $request, EntityManagerInterface $entityManager): Response
    {
        $gift = new Gift();
        $form = $this->createForm(GiftType::class, $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gift->addListGift($listGift);
            $gift->setUserGift($listGift->getUserList());
            $entityManager->persist($gift);
            $entityManager->flush();

            return $this->redirectToRoute('back_app_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/gift/new.html.twig', [
            'gift' => $gift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_show', methods: ['GET'])]
    public function show(Gift $gift): Response
    {
        return $this->render('back/gift/show.html.twig', [
            'gift' => $gift,
        ]);
        
        return $this->redirectToRoute('back_app_gift_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_gift_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gift $gift, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GiftType::class, $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_app_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/gift/edit.html.twig', [
            'gift' => $gift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_delete', methods: ['POST'])]
    public function delete(Request $request, Gift $gift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gift);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_gift_index', [], Response::HTTP_SEE_OTHER);
    }
}
