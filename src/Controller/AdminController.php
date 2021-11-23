<?php

namespace App\Controller;

use App\Entity\Medic;
use App\Form\AddMedicFormType;
use App\Form\EditMedicFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medic/adaugare-medic", name="add_medic")
     */
    public function addMedic(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(AddMedicFormType::class, new Medic());

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
            try {
                $this->entityManager->persist($medic);
                $this->entityManager->flush();
                $this->addFlash('success', 'Medicul a fost adăugat cu succes.');
                return new RedirectResponse($this->generateUrl('view_medici'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Există deja un medic cu același email/cnp.');
            }
        }

        return $this->render('medic/add_medic.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/actualizare-medic/{id}", name="edit_medic")
     */
    public function editMedic(Medic $medic, Request $request): Response
    {
        $form = $this->createForm(EditMedicFormType::class, $medic);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medic = $form->getData();
            if($form->get('administrator')->getData() === 1)
                $medic->setRoles(['ROLE_ADMIN', 'ROLE_MEDIC']);
            else
                $medic->setRoles(['ROLE_MEDIC']);
            try {
                $this->entityManager->persist($medic);
                $this->entityManager->flush();
                $this->addFlash('success', 'Medicul a fost actualizat cu succes.');
                return new RedirectResponse($this->generateUrl('view_medici'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Există deja un medic cu același email/cnp.');
            }
        }

        return $this->render('medic/edit_medic.html.twig', [
            'form'=>$form->createView(),
            'isAdmin'=>in_array('ROLE_ADMIN', $medic->getRoles()) ? 1 : 0
        ]);
    }

    /**
     * @Route("/medic/stergere-medic/{id}", name="delete_medic")
     */
    public function deleteMedic(Medic $medic): JsonResponse
    {
        try{
            $this->entityManager->remove($medic);
            $this->entityManager->flush();
            return new JsonResponse(['type'=>'success', 'message'=>'Medicul a fost șters cu succes.']);
        }
        catch (\Exception $exception) {
            return new JsonResponse(['type'=>'danger', 'message'=>'A apărut o problemă']);
        }
    }
}