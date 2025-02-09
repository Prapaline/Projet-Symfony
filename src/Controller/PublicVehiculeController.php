<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VehiculeSearchType;

#[Route('/public')]
class PublicVehiculeController extends AbstractController
{
    #[Route('/vehicules', name: 'public_vehicules_index')]
public function index(Request $request, VehiculeRepository $vehiculeRepository): Response
{
    $form = $this->createForm(VehiculeSearchType::class);
    $form->handleRequest($request);

    $vehicules = $vehiculeRepository->findAll();

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $vehicules = $vehiculeRepository->searchVehicules($data);
    }

    foreach ($vehicules as $vehicule) {
        $vehicule->disponible = $vehicule->isDisponible();
    }

    return $this->render('public/vehicules.html.twig', [
        'searchForm' => $form->createView(),
        'vehicules'  => $vehicules,
    ]);
}}