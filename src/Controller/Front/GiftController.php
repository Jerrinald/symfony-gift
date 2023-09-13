<?php

namespace App\Controller\Front;

use App\Entity\Gift;
use App\Form\GiftType;
use App\Repository\GiftRepository;
use App\Repository\ListGiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/gift')]
class GiftController extends AbstractController
{
    #[Route('/', name: 'app_gift_index', methods: ['GET', 'POST'])]
    public function index(Request $request, GiftRepository $giftRepository, ListGiftRepository $listGiftRepository): Response
    {
        $user = $this->getUser();

        $session = $request->getSession();

        if ($session->has('selected_list_id_' . $user->getId())) {
            $selectedListId = $session->get('selected_list_id_' . $user->getId());
            $listSelected = $listGiftRepository->find($selectedListId);
        }

        if ($request->isMethod('POST')) {
            $selectedListId = $request->request->get('selectedListId');
            $listSelected = $listGiftRepository->find($selectedListId);

            if (!$listSelected) {
                return $this->render('front/gift/index.html.twig', [
                    'list_gifts' => $listGiftRepository->findBy(['userList' => $this->getUser()]),
                ]);
            }

            //$userLists = $listGiftRepository->findBy(['userList' => $user]);

            // Store the selected user's ID in a session variable
            $session->set('selected_list_id_' . $user->getId(), $selectedListId);

        }

        if (isset($listSelected)) {
            $gifts = $listSelected->getGift();

            return $this->render('front/gift/index.html.twig', [
                'listSelected' => $listSelected,
                'list_gifts' => $listGiftRepository->findBy(['userList' => $this->getUser()]),
                'gifts' => $gifts,
            ]);
        }

        return $this->render('front/gift/index.html.twig', [
            'list_gifts' => $listGiftRepository->findBy(['userList' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_gift_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gift = new Gift();
        $form = $this->createForm(GiftType::class, $gift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gift->setUserGift($this->getUser());
            $entityManager->persist($gift);
            $entityManager->flush();

            return $this->redirectToRoute('front_app_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/gift/new.html.twig', [
            'gift' => $gift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gift_show', methods: ['GET'])]
    public function show(Gift $gift): Response
    {
        if ($gift->getUserGift() == $this->getUser()) {
            return $this->render('front/gift/show.html.twig', [
                'gift' => $gift,
            ]);
        }
        
        return $this->redirectToRoute('front_app_default', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_gift_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gift $gift, EntityManagerInterface $entityManager): Response
    {
        if ($gift->getUserGift() == $this->getUser()) {
            $form = $this->createForm(GiftType::class, $gift);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('front_app_gift_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('front/gift/edit.html.twig', [
                'gift' => $gift,
                'form' => $form,
            ]);
        }

        return $this->redirectToRoute('front_app_default', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_gift_delete', methods: ['POST'])]
    public function delete(Request $request, Gift $gift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gift);
            $entityManager->flush();
        }

        return $this->redirectToRoute('front_app_gift_index', [], Response::HTTP_SEE_OTHER);
    }
}
