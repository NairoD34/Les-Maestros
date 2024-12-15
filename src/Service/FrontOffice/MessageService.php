<?php

namespace App\Service\FrontOffice;

use App\Entity\Message;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MessageService
{
    private $formFactory;
    private $upload;
    private $em;


    public function __construct(FormFactoryInterface $formFactory,  EntityManagerInterface $emi)
    {
        $this->formFactory = $formFactory;
   
        $this->em = $emi;

    }


    public function handleMessage(
        Message   $message,
        Request $request,
    )
    {
        $form = $this->formFactory->create(MessageFormType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->em->persist($message);
            $this->em->flush();
            return $form;
            
        }

        return $form;
        
    }
    
}