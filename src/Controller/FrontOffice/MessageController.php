<?php

namespace App\Controller\FrontOffice;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Service\FrontOffice\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class MessageController extends AbstractController
{

    #[Route('new_message', name: 'app_new_message')]
    public function new(
        Message $message,
        Request $request,
        Security $security,
        MessageRepository $messageRepo,
        MessageService $formHandler,

    ): Response
    {
        $message = new Message();
        $formResult = $formHandler->handleMessage($message, $request);

        if (!$formResult) {
            return $this->redirectToRoute('app_index');
        };
        return $this->render('Message/message_new.html.twig', [
            'form' => $formResult,
        ]);
    }

}

   