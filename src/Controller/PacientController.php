<?php

namespace App\Controller;

use App\Entity\Pacient;
use App\Form\PacientProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PacientController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/pacient/{id}/profil", name="pacient_profile")
     */
    public function pacientProfile(Pacient $pacient, Request $request): Response
    {
        $form = $this->createForm(PacientProfileFormType::class, $pacient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            dd($form->getData());
            $pacientUpdate = $form->getData();
            $this->entityManager->persist($pacientUpdate);
            $this->entityManager->flush();
        }

        return $this->render('pacient/profile.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}