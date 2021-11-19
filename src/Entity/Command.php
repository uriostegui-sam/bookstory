<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commands")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Livre::class, inversedBy="commands")
     */
    private $produits;

    /**
     * @ORM\Column(type="datetime")
     * @Timestampable(on="update")
     */
    private $dateCommand;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Livre $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Livre $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function getDateCommand(): ?\DateTimeInterface
    {
        return $this->dateCommand;
    }

    public function setDateCommand(\DateTimeInterface $dateCommand): self
    {
        $this->dateCommand = $dateCommand;

        return $this;
    }
}
