<?php

namespace App\Controller;

use App\Entity\Medic;
use App\Form\EditMedicFormType;
use App\Form\MediciFiltersType;
use App\Form\MedicProfileFormType;
use App\Form\PacientiFiltersType;
use App\Repository\MedicRepository;
use App\Services\JsonSerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
     * @Route("/medic/vizualizare/{id}", name="view_medic")
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
}