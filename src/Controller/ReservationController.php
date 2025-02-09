<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Entity\Vehicule;
use App\Entity\Commentaire;
use App\Form\CommentaireType;

use App\Controller\CommentaireController;

use App\Repository\ReservationRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('/new/{id}', name: 'app_reservation_client', methods: ['GET', 'POST'])]
    public function reserver(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository): Response
{
    // Créer une nouvelle réservation
    $reservation = new Reservation();
    
    // Affecter automatiquement le véhicule et l'utilisateur connecté
    $reservation->setVehicules($vehicule);
    $reservation->setUsers($this->getUser());
    
    // Créer le formulaire de réservation
    $form = $this->createForm(ReservationType::class, $reservation);
    
    // Gestion de la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier que la date de début est bien antérieure à la date de fin
        if ($reservation->getDateDebut() >= $reservation->getDateFin()) {
            $this->addFlash('error', 'La date de début doit être antérieure à la date de fin.');
        } else {
            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            $diff = $dateDebut->diff($dateFin);
            $nbJours = $diff->days;
            $prixVehicule = $vehicule->getPrix();
            $prixTotal = $prixVehicule * $nbJours;
            if ($prixTotal > 400) {
                $prixTotal *= 0.9;  // Réduction de 10%
            }
            $reservation->setPrixTotal($prixTotal);

            // Enregistrer la réservation
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Ajouter un message de confirmation
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');
    
            // Rediriger après la réservation
            return $this->redirectToRoute('public_vehicules_index');
        }
    }

    // Récupérer les commentaires associés au véhicule
    $comments = $commentaireRepository->findBy(['vehicule' => $vehicule]);

    // Calculer la moyenne des notes
    $averageRating = 0;
    if (count($comments) > 0) {
        $totalRating = 0;
        foreach ($comments as $comment) {
            $totalRating += $comment->getNote();
        }
        $averageRating = $totalRating / count($comments);
    }

    // Vérifier si l'utilisateur a déjà réservé ce véhicule
    $existingReservation = $entityManager->getRepository(Reservation::class)
        ->findOneBy([
            'vehicules' => $vehicule, 
            'users' => $this->getUser(),
        ]);

    $commentForm = null;
    if ($existingReservation) {
        // Si la réservation existe, permettre à l'utilisateur de laisser un commentaire
        $commentaire = new Commentaire();
        $commentaire->setReservations($existingReservation);
        $commentaire->setVehicule($vehicule); // Lier le commentaire au véhicule

        $commentForm = $this->createForm(CommentaireType::class, $commentaire);

        // Gestion de la soumission du formulaire de commentaire
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // Enregistrer le commentaire
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté.');

            // Rediriger après la soumission du commentaire
            return $this->redirectToRoute('app_reservation_client', ['id' => $vehicule->getId()]);
        }
    }

    return $this->render('reservation/client.html.twig', [
        'reservation' => $reservation,
        'form' => $form->createView(),
        'vehicule' => $vehicule, 
        'comments' => $comments, 
        'averageRating' => $averageRating, 
        'commentForm' => $commentForm ? $commentForm->createView() : null,
    ]);
}
}
