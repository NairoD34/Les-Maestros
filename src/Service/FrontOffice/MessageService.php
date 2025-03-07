<?php

namespace App\Service\FrontOffice;

use App\Entity\Message;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

// Service pour gérer les messages dans le front-office.
class MessageService
{
    private $formFactory;
    private $upload;
    private $em;


    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $emi)
    {
        $this->formFactory = $formFactory;

        $this->em = $emi;

    }


    /**
     * Handle message form.
     */
    //Methode pour traiter le formulaire de message.
    public function handleMessage(
        Message $message,
        Request $request,
    )
    {
        $form = $this->formFactory->create(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($message);
            $this->em->flush();

            return ['form' => $form,
                'validate' => true];

        }
        return ['form' => $form,
            'validate' => false];

    }

}