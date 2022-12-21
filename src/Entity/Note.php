<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

//pour ajouter des contrainte sur un parametre de l'entité
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 20, notInRangeMessage: 'Vous devez mettre une note entre {{ min }} et {{ max }}')]
    private ?int $valeurNote = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recette $recette_id = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeurNote(): ?int
    {
        return $this->valeurNote;
    }

    public function setValeurNote(int $valeurNote): self
    {
        $this->valeurNote = $valeurNote;

        return $this;
    }

    public function getRecetteId(): ?Recette
    {
        return $this->recette_id;
    }

    public function setRecetteId(?Recette $recette_id): self
    {
        $this->recette_id = $recette_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
