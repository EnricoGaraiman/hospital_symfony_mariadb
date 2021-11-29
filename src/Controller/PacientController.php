<?php

namespace App\Controller;

use App\Entity\Consultatie;
use App\Entity\Pacient;
use App\Form\PacientProfileFormType;
use App\Repository\ConsultatieRepository;
use App\Services\JsonSerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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
}