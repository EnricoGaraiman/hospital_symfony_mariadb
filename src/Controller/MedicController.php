<?php

namespace App\Controller;

use App\Entity\Medic;
use App\Form\MedicFormType;
use App\Form\MedicProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MedicController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medic/{id}/profil", name="medic_profile")
     */
    public function medicProfile(Medic $medic, Request $request): Response
    {
        $form = $this->createForm(MedicProfileFormType::class, $medic);
        $alert = false;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medicUpdate = $form->getData();
            $this->entityManager->persist($medicUpdate);
            $this->entityManager->flush();
            $alert = ['type'=>'success', 'message'=>'Profilul a fost actualizat cu succes!'];
        }

        return $this->render('medic/profile.html.twig', [
            'form'=>$form->createView(),
            'alert'=>$alert
        ]);
    }

    /**
     * @Route("/medic/vizualizare-medici", name="view_medici")
     */
    public function viewMedici(Request $request): Response
    {
//        $form = $this->createForm(MedicFormType::class, new Medic());
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $medic = $form->getData();
//            $medic->setPassword(
//                $userPasswordHasherInterface->hashPassword(
//                    $medic,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//            $medic->setIsVerified(true);
//            if($form->get('administrator')->getData() === 1)
//                $medic->setRoles(['ROLE_ADMIN', 'ROLE_MEDIC']);
//            else
//                $medic->setRoles(['ROLE_MEDIC']);
//            $this->entityManager->persist($medic);
//            $this->entityManager->flush();
////            $alert = ['type'=>'success', 'message'=>'Profilul a fost actualizat cu succes!']; redirect aici
//        }

        return $this->render('medic/view_medici.html.twig', [
//            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/adaugare-medic", name="add_medic")
     */
    public function addMedic(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(MedicFormType::class, new Medic());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medic = $form->getData();
            $medic->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $medic,
                    $form->get('plainPassword')->getData()
                )
            );
            $medic->setIsVerified(true);
            if($form->get('administrator')->getData() === 1)
                $medic->setRoles(['ROLE_ADMIN', 'ROLE_MEDIC']);
            else
                $medic->setRoles(['ROLE_MEDIC']);
            $this->entityManager->persist($medic);
            $this->entityManager->flush();
            $this->addFlash('success', 'Medicul a fost adăugat cu succes.');
            return new RedirectResponse($this->generateUrl('view_medici'));
        }

        return $this->render('medic/add_medic.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}