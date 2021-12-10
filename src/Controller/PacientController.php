<?php

namespace App\Controller;

use App\Entity\Consultatie;
use App\Entity\Pacient;
use App\Form\PacientProfileFormType;
use App\Repository\ConsultatieRepository;
use App\Repository\MedicRepository;
use App\Services\JsonSerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/pacient/dashboard", name="dashboard_pacient")
     */
    public function dashboard(): Response
    {
        return $this->render('pacient/dashboard.html.twig');
    }

    /**
     * @Route("/pacient/dashboard/distributie", name="dashboard_distribution_pacient")
     */
    public function dashboardDistributionJson(ConsultatieRepository $consultatieRepository): JsonResponse
    {
        $idPacient = $this->getUser()->getId();
        $data = [
            'Medici' => $consultatieRepository->getNumberOfMediciForPacient($idPacient),
            'Consultații' => $consultatieRepository->getNumberOfConsultatiiForPacient($idPacient),
            'Medicamente' => $consultatieRepository->getNumberOfMedicamenteForPacient($idPacient),
        ];
        arsort($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/pacient/dashboard/top-medici", name="dashboard_top_medici_for_pacient")
     */
    public function dashboardTopMediciJson(): JsonResponse
    {
        $data = [];
        $consultatii = $this->entityManager->getRepository(Consultatie::class)->findBy(['pacient'=>$this->getUser()->getId()]);
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
     * @Route("/pacient/{id}/profil", name="pacient_profile")
     */
    public function pacientProfile(Pacient $pacient, Request $request): Response
    {
        $form = $this->createForm(PacientProfileFormType::class, $pacient);
        $alert = false;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pacientUpdate = $form->getData();
            $this->entityManager->persist($pacientUpdate);
            $this->entityManager->flush();
            $alert = ['type'=>'success', 'message'=>'Profilul a fost actualizat cu succes!'];
        }

        return $this->render('pacient/profile.html.twig', [
            'form'=>$form->createView(),
            'alert'=>$alert
        ]);
    }

    /**
     * @Route("/pacient/vizualizare-consultatii", name="pacient_view_consultatii")
     */
    public function pacientViewConsultatii(): Response
    {
        return $this->render('pacient/view_consultatii.html.twig');
    }

    /**
     * @Route("/pacient/vizualizare-consultatii-pacient-json", name="view_consultatii_pacient_json")
     */
    public function viewConsultatiiPacientJson(Request $request, JsonSerializerService $jsonSerializerService, ConsultatieRepository $consultatieRepository): Response
    {
        $response = [];
        $consultatii = $consultatieRepository->getConsultatiiForPacient($this->getUser()->getId(), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfConsultatii = $consultatieRepository->getConsultatiiForPacient($this->getUser()->getId(), $request->get('itemi'), $request->get('pagina'), true);
        $consultatiiArray = $jsonSerializerService->jsonSerializer($consultatii, [
            'id','data', 'medic' => ['prenumeMedic', 'numeMedic'],
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
     * @Route("/pacient/vizualizare-consultatie/{id}", name="view_consultatie_pacient")
     */
    public function viewConsultatie(Consultatie $consultatie): Response
    {
        if($consultatie->getPacient()->getId() !== $this->getUser()->getId() and !$this->isGranted('ROLE_ADMIN')) {
            $this->denyAccessUnlessGranted('', 'Acces interzis', 'Nu îți este permis să vizualizezi această consultație');
        }
        return $this->render('consultatie/view_consultatie.html.twig', [
            'consultatie'=>$consultatie,
        ]);
    }

    /**
     * @Route("/pacient/vizualizare-medici", name="pacient_view_medici")
     */
    public function pacientViewMedici(): Response
    {
        return $this->render('pacient/view_medici.html.twig');
    }

    /**
     * @Route("/pacient/vizualizare-medici-json", name="pacient_view_medici_json")
     */
    public function viewMediciForPacientJson(Request $request, JsonSerializerService $jsonSerializerService, MedicRepository $medicRepository): Response
    {
        $response = [];
        $medici = $medicRepository->getMediciForPacient($this->getUser()->getId(), $request->get('itemi'), $request->get('pagina'), false);
        $numberOfMedici = $medicRepository->getMediciForPacient($this->getUser()->getId(), $request->get('itemi'), $request->get('pagina'), true);
        $mediciArray = $jsonSerializerService->jsonSerializer($medici, ['id', 'prenumeMedic', 'numeMedic', 'email', 'specializare']);
        $response['medici'] = $mediciArray;
        $response['pagina'] = $request->get('pagina');
        $response['numberOfPages'] = ceil($numberOfMedici / intval($request->get('itemi')));
        $response['numberOfRows'] = $numberOfMedici;
        $response['offset'] = ((int)$request->get('pagina') - 1) * (int)$request->get('itemi');
        return new JsonResponse($response);
    }

}