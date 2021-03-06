<?php

namespace App\Controller;

use App\Entity\Consultatie;
use App\Entity\Medic;
use App\Entity\Medicament;
use App\Entity\Pacient;
use App\Form\AddPacientFormType;
use App\Form\ConsultatieFormType;
use App\Form\ConsultatiiFiltersType;
use App\Form\EditPacientFormType;
use App\Form\MedicamentFormType;
use App\Form\MediciFiltersType;
use App\Form\MedicProfileFormType;
use App\Form\PacientiFiltersType;
use App\Repository\ConsultatieRepository;
use App\Repository\MedicamentRepository;
use App\Repository\MedicRepository;
use App\Repository\PacientRepository;
use App\Services\EmailServices;
use App\Services\JsonSerializerService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;
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
    private $emailServices;

    public function __construct(EntityManagerInterface $entityManager, EmailServices $emailServices) {
        $this->entityManager = $entityManager;
        $this->emailServices = $emailServices;
    }

    /**
     * @Route("/medic/dashboard", name="dashboard")
     */
    public function dashboard(ConsultatieRepository $consultatieRepository, PacientRepository $pacientRepository): Response
    {
        $lastConsultatii = $consultatieRepository->getLastConsultatii(5);
        $lastPacienti = $pacientRepository->getLastPacienti(5);

        return $this->render('dashboard/index.html.twig', [
            'lastConsultatii' => $lastConsultatii,
            'lastPacienti' => $lastPacienti,
        ]);
    }

    /**
     * @Route("/medic/dashboard/distributie", name="dashboard_distribution")
     */
    public function dashboardDistributionJson(): JsonResponse
    {
        $data = [
            'Medici' => count($this->entityManager->getRepository(Medic::class)->findAll()),
            'Pacien??i' => count($this->entityManager->getRepository(Pacient::class)->findAll()),
            'Consulta??ii' => count($this->entityManager->getRepository(Consultatie::class)->findAll()),
            'Medicamente' => count($this->entityManager->getRepository(Medicament::class)->findAll()),
        ];
        arsort($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/medic/dashboard/top-medici", name="dashboard_top_medici")
     */
    public function dashboardTopMediciJson(): JsonResponse
    {
        $data = [];
        $consultatii = $this->entityManager->getRepository(Consultatie::class)->findAll();
        foreach ($consultatii as $consultatie) {
            $key = $consultatie->getMedic()->getPrenumeMedic() . ' ' . $consultatie->getMedic()->getNumeMedic();
            if(array_key_exists($key, $data)) {
                $data[$key] += 1;
            }
            else {
                $data[$key] = 1;
            }
        }
        arsort($data);
        if(count($data) > 10) {
            $data = array_slice($data, 0, 10, true);
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/medic/{id}/profil", name="medic_profile")
     */
    public function medicProfile(Medic $medic, Request $request): Response
    {
        if($this->getUser()->getId() !== $medic->getId() and !$this->isGranted('ROLE_ADMIN')) {
            $this->denyAccessUnlessGranted('', 'Acces interzis', 'Nu ????i este permis s?? vizualizezi aceast?? pagin??');
        }

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
        $response['offset'] = ((int)$request->get('pagina') - 1) * (int)$request->get('itemi');
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
        $pacienti = $pacientRepository->getPacientiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), false, $this->getUser()->getId());
        $numberOfPacienti = $pacientRepository->getPacientiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), true, $this->getUser()->getId());
        $pacientiArray = $jsonSerializerService->jsonSerializer($pacienti, ['id','prenumePacient', 'numePacient', 'email', 'cnp', 'adresa', 'asigurare']);
        $response['pacienti'] = $pacientiArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfPacienti / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfPacienti;
        $response['offset'] = ((int)$request->get('pagina') - 1) * (int)$request->get('itemi');
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
            if($this->entityManager->getRepository(Medic::class)->findOneBy(['email' => $pacient->getEmail()]) !== null) {
                $this->addFlash('error', 'Exist?? deja un cont cu acest email.');
                return $this->redirectToRoute('add_pacient');
            }
            $pacient->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $pacient,
                    $form->get('plainPassword')->getData()
                )
            );
            $pacient->setIsVerified(true)
                ->setRoles(['ROLE_PACIENT']);
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $this->entityManager->persist($pacient);
                $this->entityManager->persist($this->getUser()->addPacient($pacient));
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
                $this->addFlash('success', 'Pacientul a fost ad??ugat cu succes.');
                $this->emailServices->sendEmail($pacient->getEmail(), 'Bine ai venit pe platform??', 'emails/new_user.html.twig', [
                    'password' => $form->get('plainPassword')->getData()
                ]);
                return new RedirectResponse($this->generateUrl('view_pacienti'));
            } catch (UniqueConstraintViolationException $e) {
                $this->entityManager->getConnection()->rollBack();
                $this->addFlash('error', 'Exist?? deja un pacient cu acela??i email/cnp.');
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
                $this->addFlash('error', 'Exist?? deja un pacient cu acela??i email/cnp.');
                return new RedirectResponse($this->generateUrl('edit_pacient', ['id' => $pacient->getId()]));
            }
        }

        return $this->render('pacient/edit_pacient.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/vizualizare-medicamente", name="view_medicamente")
     */
    public function viewMedicamente(): Response
    {
        return $this->render('medicament/view_medicamente.html.twig');
    }

    /**
     * @Route("/medic/vizualizare-medicamente-json", name="view_medicamente_json")
     */
    public function viewMedicamenteJson(Request $request, JsonSerializerService $jsonSerializerService, MedicamentRepository $medicamentRepository): Response
    {
        $response = [];
        $medicamente = $medicamentRepository->getMedicamenteByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfMedicamente = $medicamentRepository->getMedicamenteByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), true);
        $medicamenteArray = $jsonSerializerService->jsonSerializer($medicamente, ['id','denumire']);
        $response['medicamente'] = $medicamenteArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfMedicamente / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfMedicamente;
        $response['offset'] = ((int)$request->get('pagina') - 1) * (int)$request->get('itemi');
        return new JsonResponse($response);
    }

    /**
     * @Route("/medic/adaugare-medicament", name="add_medicament")
     */
    public function addMedicament(Request $request): Response
    {
        $form = $this->createForm(MedicamentFormType::class, new Medicament());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            $this->addFlash('success', 'Medicamentul a fost ad??ugat cu succes.');
            return new RedirectResponse($this->generateUrl('view_medicamente'));
        }

        return $this->render('medicament/add_edit_medicament.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/actualizare-medicament/{id}", name="edit_medicament")
     */
    public function editMedicament(Medicament $medicament, Request $request): Response
    {
        $form = $this->createForm(MedicamentFormType::class, $medicament);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            $this->addFlash('success', 'Medicamentul a fost actualizat cu succes.');
            return new RedirectResponse($this->generateUrl('view_medicamente'));
        }

        return $this->render('medicament/add_edit_medicament.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/vizualizare-consultatii", name="view_consultatii")
     */
    public function viewConsultatii(): Response
    {
        $filters = $this->createForm(ConsultatiiFiltersType::class);

        return $this->render('consultatie/view_consultatii.html.twig', [
            'filters'=>$filters->createView(),
        ]);
    }

    /**
     * @Route("/medic/vizualizare-consultatii-json", name="view_consultatii_json")
     */
    public function viewConsultatiiJson(Request $request, JsonSerializerService $jsonSerializerService, ConsultatieRepository $consultatieRepository): Response
    {
        $response = [];
        $consultatii = $consultatieRepository->getConsultatiiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfConsultatii = $consultatieRepository->getConsultatiiByFilters($request->get('filtre'), $request->get('itemi'), $request->get('pagina'), true);
        $consultatiiArray = $jsonSerializerService->jsonSerializer($consultatii, [
            'id','data', 'medic' => ['prenumeMedic', 'numeMedic'], 'pacient' => ['prenumePacient', 'numePacient'],
            'diagnostic', 'medicament' => ['id', 'denumire'], 'dozaMedicament'
        ]);
        $response['consultatii'] = $consultatiiArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfConsultatii / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfConsultatii;
        $response['offset'] = ((int)$request->get('pagina') - 1) * (int)$request->get('itemi');
        return new JsonResponse($response);
    }

    /**
     * @Route("/medic/vizualizare-consultatie/{id}", name="view_consultatie")
     */
    public function viewConsultatie(Consultatie $consultatie): Response
    {
        return $this->render('consultatie/view_consultatie.html.twig', [
            'consultatie'=>$consultatie,
        ]);
    }

    /**
     * @Route("/medic/adaugare-consultatie", name="add_consultatie")
     */
    public function addConsultatie(Request $request): Response
    {
        $form = $this->createForm(ConsultatieFormType::class, new Consultatie());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $consultatie = $form->getData();
            $consultatie->setMedic($this->getUser());
            $consultatie->setData((new \DateTime()));
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $this->entityManager->persist($consultatie);
                $this->entityManager->persist($this->getUser()->addPacient($consultatie->getPacient()));
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
                $this->addFlash('success', 'Consulta??ia a fost ad??ugat?? cu succes.');
                // Send email to pacient to see results
                $this->emailServices->sendEmail($consultatie->getPacient()->getEmail(), 'Rezultate consultatie', 'emails/consultatie.html.twig', [
                    'consultatie' => $consultatie
                ]);
                return new RedirectResponse($this->generateUrl('view_consultatii'));
            } catch (Exception $e) {
                $this->entityManager->getConnection()->rollBack();
                $this->addFlash('error', 'A ap??rut o problem??.');
                return new RedirectResponse($this->generateUrl('view_consultatii'));
            }
        }

        return $this->render('consultatie/add_edit_consultatie.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/medic/pacienti-consultatie", name="get_pacienti_for_consultatie")
     */
    public function getPacientiForConsultatie(Request $request, PacientRepository $pacientRepository): JsonResponse
    {
        return new JsonResponse($pacientRepository->getPacientiForConsultatie($request->get('search')));
    }

    /**
     * @Route("/medic/medicamente-consultatie", name="get_medicamente_for_consultatie")
     */
    public function getMedicamenteiForConsultatie(Request $request, MedicamentRepository $medicamentRepository): JsonResponse
    {
        return new JsonResponse($medicamentRepository->getMedicamenteForConsultatie($request->get('search')));
    }

    /**
     * @Route("/medic/medici-consultatie", name="get_medici_for_consultatie")
     */
    public function getMediciForConsultatie(Request $request, MedicRepository $medicRepository): JsonResponse
    {
        return new JsonResponse($medicRepository->getMediciForConsultatie($request->get('search')));
    }

    /**
     * @Route("/medic/actualizare-consultatie/{id}", name="edit_consultatie")
     */
    public function editConsultatie(Consultatie $consultatie, Request $request): Response
    {
        $form = $this->createForm(ConsultatieFormType::class, $consultatie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $consultatie = $form->getData();
            $consultatie->setMedic($this->getUser());
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $this->entityManager->persist($consultatie);
                $this->entityManager->persist($this->getUser()->addPacient($consultatie->getPacient()));
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
                $this->addFlash('success', 'Consulta??ia a fost actualizat?? cu succes.');
                // Send email to pacient to see results
                $this->emailServices->sendEmail($consultatie->getPacient()->getEmail(), 'Actualizare rezultate consultatie', 'emails/consultatie_actualizata.html.twig', [
                    'consultatie' => $consultatie
                ]);
                return new RedirectResponse($this->generateUrl('view_consultatii'));
            } catch (Exception $e) {
                $this->entityManager->getConnection()->rollBack();
                $this->addFlash('error', 'A ap??rut o problem??.');
                return new RedirectResponse($this->generateUrl('view_consultatii'));
            }
        }

        return $this->render('consultatie/add_edit_consultatie.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}