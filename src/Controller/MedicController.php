<?php

namespace App\Controller;

use App\Entity\Medic;
use App\Entity\Pacient;
use App\Form\AddPacientFormType;
use App\Form\EditPacientFormType;
use App\Form\MediciFiltersType;
use App\Form\MedicProfileFormType;
use App\Form\PacientiFiltersType;
use App\Repository\MedicRepository;
use App\Repository\PacientRepository;
use App\Services\JsonSerializerService;
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
 * @isGranted("ROLE_MEDIC")
 */
class MedicController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medic/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig');
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
    public function viewMedici(): Response
    {
        $filters = $this->createForm(MediciFiltersType::class);

        return $this->render('medic/view_medici.html.twig', [
            'filters'=>$filters->createView(),
        ]);
    }

    /**
     * @Route("/medic/vizualizare-medici-json", name="view_medici_json")
     */
    public function viewMediciJson(Request $request, JsonSerializerService $jsonSerializerService, MedicRepository $medicRepository): Response
    {
        $response = [];
        $medici = $medicRepository->getMediciByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfMedici = $medicRepository->getMediciByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), true);
        $mediciArray = $jsonSerializerService->jsonSerializer($medici, ['id','prenumeMedic', 'numeMedic', 'email', 'roles', 'specializare']);
        $response['medici'] = $mediciArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfMedici / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfMedici;
        return new JsonResponse($response);
    }

    /**
     * @Route("/medic/vizualizare-medic/{id}", name="view_medic")
     */
    public function editMedic(Medic $medic): Response
    {
        return $this->render('medic/view_medic.html.twig', [
            'medic'=>$medic,
        ]);
    }

    /**
     * @Route("/medic/vizualizare-pacienti", name="view_pacienti")
     */
    public function viewPacienti(): Response
    {
        $filters = $this->createForm(PacientiFiltersType::class);

        return $this->render('pacient/view_pacienti.html.twig', [
            'filters'=>$filters->createView(),
        ]);
    }

    /**
     * @Route("/medic/vizualizare-pacienti-json", name="view_pacienti_json")
     */
    public function viewPacientiJson(Request $request, JsonSerializerService $jsonSerializerService, PacientRepository $pacientRepository): Response
    {
        $response = [];
        $medici = $pacientRepository->getPacientiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfMedici = $pacientRepository->getPacientiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), true);
        $mediciArray = $jsonSerializerService->jsonSerializer($medici, ['id','prenumePacient', 'numePacient', 'email', 'cnp', 'adresa', 'asigurare']);
        $response['pacienti'] = $mediciArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfMedici / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfMedici;
        return new JsonResponse($response);
    }

    /**
     * @Route("/medic/vizualizare-pacient/{id}", name="view_pacient")
     */
    public function viewPacient(Pacient $pacient): Response
    {
        return $this->render('pacient/view_pacient.html.twig', [
            'pacient'=>$pacient,
        ]);
    }

    /**
     * @Route("/medic/adaugare-pacient", name="add_pacient")
     */
    public function addPacient(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(AddPacientFormType::class, new Pacient());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pacient = $form->getData();
            $pacient->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $pacient,
                    $form->get('plainPassword')->getData()
                )
            );
            $pacient->setIsVerified(true)
                ->setRoles(['ROLE_PACIENT']);
            try {
                $this->entityManager->persist($pacient);
                $this->entityManager->flush();
                $this->addFlash('success', 'Pacientul a fost adăugat cu succes.');
                return new RedirectResponse($this->generateUrl('view_pacienti'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Există deja un pacient cu același email/cnp.');
            }
        }

        return $this->render('pacient/add_pacient.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/actualizare-pacient/{id}", name="edit_pacient")
     */
    public function editPacient(Pacient $pacient, Request $request): Response
    {
        $form = $this->createForm(EditPacientFormType::class, $pacient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pacient = $form->getData();
            try {
                $this->entityManager->persist($pacient);
                $this->entityManager->flush();
                $this->addFlash('success', 'Pacientul a fost actualizat cu succes.');
                return new RedirectResponse($this->generateUrl('view_pacienti'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Există deja un pacient cu același email/cnp.');
            }
        }

        return $this->render('pacient/edit_pacient.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/stergere-pacient/{id}", name="delete_pacient")
     */
    public function deleteMedic(Pacient $pacient): JsonResponse
    {
        try{
            $this->entityManager->remove($pacient);
            $this->entityManager->flush();
            return new JsonResponse(['type'=>'success', 'message'=>'Pacientul a fost șters cu succes.']);
        }
        catch (\Exception $exception) {
            return new JsonResponse(['type'=>'danger', 'message'=>'A apărut o problemă']);
        }
    }
}