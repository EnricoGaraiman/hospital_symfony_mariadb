<?php

namespace App\Controller;

use App\Entity\Medic;
use App\Form\MedicFormType;
use App\Form\MediciPacientiFiltersType;
use App\Form\MedicProfileFormType;
use App\Repository\MedicRepository;
use App\Services\JsonSerializerService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $filters = $this->createForm(MediciPacientiFiltersType::class);

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
        $response['numberOfRows'] = count($mediciArray);
        return new JsonResponse($response);
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