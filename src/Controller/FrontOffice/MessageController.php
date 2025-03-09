<?php

namespace App\Controller\FrontOffice;

use App\Entity\Message;
use App\Service\FrontOffice\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Classe pour gérer les opérations liées aux messages.
class MessageController extends AbstractController
{

    //Route pour afficher la page de nouveau message.
    #[Route('/new_message', name: 'app_new_message')]
    public function new(
        Request        $request,
        MessageService $formHandler,

    ): Response
    {
        $newMessage = new Message();
        $formResult = $formHandler->handleMessage($newMessage, $request);
        if ($formResult["validate"]) {
            // Redirige vers la page d'accueil si la validation est réussie.
            return $this->redirectToRoute('app_index');
        }

        return $this->render('FrontOffice/message/message_new.html.twig', [
            'form' => $formResult["form"],
        ]);
    }

}

   