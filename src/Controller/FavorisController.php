<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Vehicule;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    #[Route('/favoris/toggle/{id}', name: 'favoris_toggle', methods: ['POST'])]
    public function toggleFavoris(Vehicule $vehicule, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté.'], 403);
        }

        $favoris = $entityManager->getRepository(Favoris::class)
            ->findOneBy(['user' => $user, 'vehicule' => $vehicule]);

        if ($favoris) {
            $entityManager->remove($favoris);
            $entityManager->flush();

            return new JsonResponse(['status' => 'removed']);
        } else {
            $newFavoris = new Favoris();
            $newFavoris->setUser($user);
            $newFavoris->setVehicule($vehicule);

            $entityManager->persist($newFavoris);
            $entityManager->flush();

            return new JsonResponse(['status' => 'added']);
        }
    }
    /**
     * @Route("/favoris/{id}/toggle", name="app_favoris_toggle", methods={"POST"})
     */
    public function favoris(Vehicule $vehicule): JsonResponse
    {
        $user = $this->getUser();

        if ($user->getFavoris()->contains($vehicule)) {
            $user->removeFavori($vehicule);
            $action = 'retiré';
        } else {
            $user->addFavori($vehicule);
            $action = 'ajouté';
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'action' => $action]);
    }
}