<?php

namespace App\Controller\Front;

use App\Entity\Booking;
use App\Entity\ListGift;
use App\Entity\Gift;
use App\Entity\User;
use App\Form\GiftType;
use App\Form\GiftTypeUrl;
use App\Form\ListGiftPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\ListGiftType;
use App\Repository\ListGiftRepository;
use App\Security\EmailVerifier;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Cookie;

#[Route('/browse-list')]
class BrowseListController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EmailService $emailService;

    public function __construct(EmailVerifier $emailVerifier, EmailService $emailService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->emailService = $emailService;
    }

    #[Route('/', name: 'app_browse_list_index', methods: ['GET'])]
    public function index(ListGiftRepository $listGiftRepository): Response
    {
        $activeListGifts = $listGiftRepository->findActiveListGifts();

        return $this->render('front/browse_list/index.html.twig', [
            'list_gifts' => $activeListGifts,
        ]);
    }

    #[Route('/{id}', name: 'app_browse_list_show', methods: ['GET', 'POST'])]
    public function show(ListGift $listGift, EntityManagerInterface $entityManager, Request $request, SessionInterface $session, UrlGeneratorInterface $urlGenerator): Response
    {
        // Get the current date
        $currentDate = new \DateTime();

        // Check if the current date is between openingDate and closingDate
        if ($currentDate <= $listGift->getOpeningDate() || $currentDate >= $listGift->getClosingDate() || !$listGift->isActive()) {
            return $this->redirectToRoute('front_app_browse_list_index');
        }

        $access = false;

        $passStored = $listGift->getPassword();
        // Check if the user is logged in
        $isLoggedIn = $this->getUser() !== null;

        $form = $this->createForm(ListGiftPasswordType::class, $listGift);
        $form->handleRequest($request);

        $gift = new Gift();
        $formGift = $this->createForm(GiftType::class, $gift);
        $formGift->handleRequest($request);

        $formGiftUrl = $this->createForm(GiftTypeUrl::class, $gift);
        $formGiftUrl->handleRequest($request);

        if ($formGift->isSubmitted() && $formGift->isValid()) {

            $token = bin2hex(random_bytes(32));
            $gift->addListGift($listGift);

            $gift->setUserGift($listGift->getUserList());
            $gift->setTokenCancel($token);
            $entityManager->persist($gift);
            $entityManager->flush();
            

            // Generate the URL for the cancellation link
            $urlCancel = $urlGenerator->generate('front_app_browse_list_book_cancel', ['listGift_id' => $listGift->getId(), 'gift_id' => $gift->getId(), 'token' => $gift->getTokenCancel()], UrlGeneratorInterface::ABSOLUTE_URL);

            $destinator = $gift->getEmail();
            $htlmContent = $this->renderView('front/browse_list/booking_confirmation.html.twig', [
                'gift' => $gift,
                'list' => $listGift,
                'user' => $listGift->getUserList(),
                'urlCancel' => $urlCancel, // Pass the URL to the template
            ]);

            $subject = 'Confirmation de réservation Gift Management';

            $this->emailService->sendVerificationEmail($destinator, $subject, $htlmContent);
            

            $destinatorCreator = $listGift->getUserList()->getEmail();
            $htlmContentCreator = $this->renderView('front/browse_list/booking_confirmation_creator.html.twig', [
                'gift' => $gift,
                'list' => $listGift->getTitle(),
            ]);
            $subjectCreator = 'Un cadeau réservé pour vous sur Gift Management';

            $this->emailService->sendVerificationEmail($destinatorCreator, $subjectCreator, $htlmContentCreator);

            // @TODO Change the redirect on success and handle or remove the flash message in your templates
            $this->addFlash('success', 'Votre réservation a été pris en compte, un mail de confirmation vous a été envoyé');

            return $this->redirectToRoute('front_app_browse_list_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($formGiftUrl->isSubmitted() && $formGiftUrl->isValid()) {
            
            // Récupérez le lien soumis par le formulaire
            $link = $formGiftUrl->get('urlPurchase')->getData();

            // Utilisez Goutte pour extraire les informations du lien
            $client = new Client();
            $crawler = $client->request('GET', $link);

            $pageTitle = $crawler->filter('.item-productTitle')->first()->text();
            
            

            // Extract the image URL from the 'src' attribute
            $imageUrl = $crawler->filter('.img-cover.img-cover-contain')->attr('src');               
            
            $price = $crawler->filter('.to_pay_cash_default .price.ls-minus')->text();
            $price = preg_replace('/€/', '', $price);
            $price = preg_replace('/,/', '.', $price);
            $price = floatval($price);

            // Exemple d'extraction du titre de la page
            

            //je verifie que le tire fait moins de 255 caractères
            if (strlen($pageTitle) > 255) {
                // je reduis la taille du titre
                $pageTitle = substr($pageTitle, 0, 250);
            }
            
            //je verifie que le tire fait moins de 255 caractères
            if (strlen($link) > 255) {
                // je reduis la taille du titre
                $link = substr($pageTitle, 0, 250);
            }

            // Téléchargez l'image
            $imageContent = file_get_contents($imageUrl);

            // Générez un nom de fichier unique pour l'image
            $imageName = md5(uniqid()) . '.jpg';

            // Définissez le chemin du répertoire d'upload VichUploader
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/images/gifts';

            // Écrivez l'image téléchargée dans le répertoire d'upload
            file_put_contents($uploadDir . '/' . $imageName, $imageContent);

            // Remplissez le champ image de l'entité Gift avec le nom de fichier
            $gift->setImageName($imageName);

            $gift->setName($pageTitle);
            $gift->setPrice($price);

            // Remplissez les champs du cadeau avec les données extraites
            $gift->setFirstname($formGiftUrl->get('firstname')->getData());
            $gift->setEmail($formGiftUrl->get('email')->getData());
            $gift->setUrlPurchase($link);

            $token = bin2hex(random_bytes(32));
            $gift->addListGift($listGift);

            $gift->setUserGift($listGift->getUserList());
            $gift->setTokenCancel($token);
            $entityManager->persist($gift);
            $entityManager->flush();

            // Generate the URL for the cancellation link
            $urlCancel = $urlGenerator->generate('front_app_browse_list_book_cancel', ['listGift_id' => $listGift->getId(), 'gift_id' => $gift->getId(), 'token' => $gift->getTokenCancel()], UrlGeneratorInterface::ABSOLUTE_URL);

            $destinator = $gift->getEmail();
            $htlmContent = $this->renderView('front/browse_list/booking_confirmation.html.twig', [
                'gift' => $gift,
                'list' => $listGift,
                'user' => $listGift->getUserList(),
                'urlCancel' => $urlCancel, // Pass the URL to the template
            ]);

            $subject = 'Confirmation de réservation Gift Management';

            $this->emailService->sendVerificationEmail($destinator, $subject, $htlmContent);
            

            $destinatorCreator = $listGift->getUserList()->getEmail();
            $htlmContentCreator = $this->renderView('front/browse_list/booking_confirmation_creator.html.twig', [
                'gift' => $gift,
                'list' => $listGift->getTitle(),
            ]);
            $subjectCreator = 'Un cadeau réservé pour vous sur Gift Management';

            $this->emailService->sendVerificationEmail($destinatorCreator, $subjectCreator, $htlmContentCreator);

            // @TODO Change the redirect on success and handle or remove the flash message in your templates
            $this->addFlash('success', 'Votre réservation a été pris en compte, un mail de confirmation vous a été envoyé');

            return $this->redirectToRoute('front_app_browse_list_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            // Checking a password
            if (password_verify($password, $passStored)) {
                // Password is valid
                $access = true;
                if ($isLoggedIn) {
                    // Store the timestamp in session for the submitted listGiftId and userId
                    $session->set('submitted_list_' . $listGift->getId() . '_user_' .$this->getUser()->getId(), time(), 3600);
                }

            }
        }

        return $this->render('front/browse_list/show.html.twig', [
            'list_gift' => $listGift,
            'form' => $form->createView(),
            'formGift' => $formGift,
            'formGiftUrl' => $formGiftUrl,
            'access' => $access,
        ]);
    }

    #[Route('/book/{gift_id}/{listGift_id}', name: 'app_browse_list_book', methods: ['GET', 'POST'])]
    #[ParamConverter('gift', options: ['id' => 'gift_id'])]
    #[ParamConverter('listGift', options: ['id' => 'listGift_id'])]
    public function bookGift(Gift $gift, ListGift $listGift, Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        $booking = new Booking();

        $user = $this->getUser();
        $nomUser = $user->getNom();
        $prenomUser = $user->getPrenom();

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');

        $booking->setGift($gift);
        $booking->setListGift($listGift);
        $booking->setUserBooking($user);
        $booking->setActive(true);

        $entityManager->persist($booking);
        $entityManager->flush();

        // Generate the URL for the cancellation link
        $urlCancel = $urlGenerator->generate('front_app_booking_show', ['id' => $booking->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $destinator = $user->getEmail();
        $htlmContent = $this->renderView('front/browse_list/booking_confirmation.html.twig', [
            'nom' => $nom,
            'prenom' =>$prenom,
            'email' => $email,
            'gift' => $gift->getName(),
            'list' => $listGift->getTitle(),
            'urlCancel' => $urlCancel, // Pass the URL to the template
        ]);

        $subject = 'Confirmation de réservation Gift Management';

        $this->emailService->sendVerificationEmail($destinator, $subject, $htlmContent);
        

        $destinatorCreator = $listGift->getUserList()->getEmail();
        $htlmContentCreator = $this->renderView('front/browse_list/booking_confirmation_creator.html.twig', [
            'prenom' => $prenomUser,
            'nom' => $nomUser,
            'gift' => $gift->getName(),
            'list' => $listGift->getTitle(),
        ]);
        $subjectCreator = 'Reservation d\'un de vos cadeaux sur Gift Management';

        $this->emailService->sendVerificationEmail($destinatorCreator, $subjectCreator, $htlmContentCreator);

        return $this->redirectToRoute('front_app_booking_index');
    }

    #[Route('/{listGift_id}/cancel/{gift_id}', name: 'app_browse_list_book_cancel', methods: ['GET', 'POST'])]
    #[ParamConverter('gift', options: ['id' => 'gift_id'])]
    #[ParamConverter('listGift', options: ['id' => 'listGift_id'])]
    public function cancelBooking(Gift $gift, ListGift $listGift, Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        // Retrieve the token from the query parameters
        $token = $request->query->get('token');

        if ($token === $gift->getTokenCancel()) {
            return $this->render('front/browse_list/cancel_booking.html.twig', [
                'list_gift' => $listGift,
                'gift' => $gift,
            ]);
        }

        return $this->redirectToRoute('front_app_default');
    }

    #[Route('/{gift_id}/{listGift_id}', name: 'app_gift_delete_booking', methods: ['POST'])]
    #[ParamConverter('gift', options: ['id' => 'gift_id'])]
    #[ParamConverter('listGift', options: ['id' => 'listGift_id'])]
    public function deleteBooking(Request $request, Gift $gift, ListGift $listGift, EntityManagerInterface $entityManager): Response
    {
        $email = $gift->getUserGift()->getEmail();
        $nameGift = $gift->getName();
        $nameList = $listGift->getTitle();

        if ($this->isCsrfTokenValid('delete'.$gift->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gift);
            $entityManager->flush();

            $destinatorCreator = $email;
            $htlmContentCreator = "<h1>Réservation annulée</h1> <p>La réservation pour le cadeau " . $nameGift . " de votre liste " . $nameList . " a été annulée";
            $subjectCreator = 'Un cadeau a été retiré';

            $this->emailService->sendVerificationEmail($destinatorCreator, $subjectCreator, $htlmContentCreator);
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre cadeau a été retiré de la liste');
        return $this->redirectToRoute('front_app_browse_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
