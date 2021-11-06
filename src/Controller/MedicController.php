<?php

namespace App\Controller;

use App\Entity\Medic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicController extends AbstractController
{
    /**
     * @Route("/medic/{id}/profil", name="medic_profile")
     */
    public function pacientProfile(Medic $medic, Request $request): Response
    {
//        $form = $this->createForm(PacientProfileFormType::class, $pacient);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $pacientUpdate = $form->getData();
//            $this->entityManager->persist($pacientUpdate);
//            $this->entityManager->flush();
//        }

        return $this->render('medic/profile.html.twig', [
//            'form'=>$form->createView()
        ]);
    }
}