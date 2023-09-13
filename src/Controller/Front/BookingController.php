<?php

namespace App\Controller\Front;

use App\Entity\Booking;
use App\Entity\ListGift;
use App\Form\ListGiftType;
use App\Repository\BookingRepository;
use App\Repository\ListGiftRepository;
use App\Security\EmailVerifier;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/booking')]
class BookingController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EmailService $emailService;

    public function __construct(EmailVerifier $emailVerifier, EmailService $emailService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->emailService = $emailService;
    }

    #[Route('/', name: 'app_booking_index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('front/booking/index.html.twig', [
            'bookings' => $bookingRepository->findBy(['userBooking' => $this->getUser()]),
        ]);
    }

    #[Route('/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(Booking $booking): Response
    {
        if ($booking->getUserBooking() == $this->getUser()) {
            return $this->render('front/booking/show.html.twig', [
                'booking' => $booking,
            ]);
        }
        return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
        
    }


    #[Route('cancel/{id}', name: 'app_booking_cancel', methods: ['POST'])]
    public function cancelBooking(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('cancel'.$booking->getId(), $request->request->get('_token'))) {
            $booking->setActive(false); 
            
            $entityManager->persist($booking);
            $entityManager->flush();
        }

        $destinator = $booking->getGift()->getUserGift()->getEmail();
        $htlmContent = $this->renderView('front/booking/cancel_template.html.twig', [
            'gift' => $booking->getGift()->getName(),
            'list' => $booking->getListGift()->getTitle(),
            'nom' => $booking->getUserBooking()->getNom(),
            'prenom' => $booking->getUserBooking()->getPrenom(),
        ]);
        $subject = 'Réinitialisation du mot de passe';

        $this->emailService->sendVerificationEmail($destinator, $subject, $htlmContent);

        return $this->redirectToRoute('front_app_list_gift_index', [], Response::HTTP_SEE_OTHER);
    }
}
