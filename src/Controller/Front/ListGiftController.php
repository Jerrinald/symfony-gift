<?php

namespace App\Controller\Front;

use App\Entity\Gift;
use App\Entity\ListGift;
use App\Form\EditListGiftType;
use App\Form\GiftType;
use App\Form\ListGiftType;
use App\Repository\ListGiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Goutte\Client;

#[Route('/profile/list-gift')]
class ListGiftController extends AbstractController
{
    #[Route('/', name: 'app_list_gift_index', methods: ['GET'])]
    public function index(ListGiftRepository $listGiftRepository): Response
    {
        return $this->render('front/list_gift/index.html.twig', [
            'list_gifts' => $listGiftRepository->findBy(['userList' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_list_gift_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

            $listGift->setUserList($this->getUser());
            $entityManager->persist($listGift);
            $entityManager->flush();

            return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/list_gift/new.html.twig', [
            'list_gift' => $listGift,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_gift_show', methods: ['GET'])]
    public function show(ListGift $listGift): Response
    {
        if ($listGift->getUserList() == $this->getUser()) {
            return $this->render('front/list_gift/show.html.twig', [
                'list_gift' => $listGift,
            ]);
        }
        return $this->redirectToRoute('front_app_default', [], Response::HTTP_SEE_OTHER);
        
    }

    #[Route('/{id}/edit', name: 'app_list_gift_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        if ($listGift->getUserList() == $this->getUser()) {
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

                return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('front/list_gift/edit.html.twig', [
                'list_gift' => $listGift,
                'form' => $form,
            ]);
        }

        return $this->redirectToRoute('front_app_default', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_list_gift_delete', methods: ['POST'])]
    public function delete(Request $request, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listGift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($listGift);
            $entityManager->flush();
        }

        return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
    }
}
