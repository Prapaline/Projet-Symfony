<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Vehicule $vehicules = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $users = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicules(): ?Vehicule
    {
        return $this->vehicules;
    }

    public function setVehicules(?Vehicule $vehicules): static
    {
        $this->vehicules = $vehicules;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }
    private $prixTotal;

public function getPrixTotal(): ?float
{
    return $this->prixTotal;
}

public function setPrixTotal(float $prixTotal): self
{
    $this->prixTotal = $prixTotal;

    return $this;
}
    public function validateDates(\Symfony\Component\Validator\Context\ExecutionContextInterface $context): void
    {
        if ($this->dateDebut >= $this->dateFin) {
            $context->buildViolation('La date de début doit être antérieure à la date de fin.')
                ->atPath('dateDebut')
                ->addViolation();
        }
    }
}
