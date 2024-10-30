<?php

namespace App\Entity;

use App\enum\StatusDette;
use App\Repository\DetteRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DetteRepository::class)]
class Dette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $montant;

    #[ORM\Column(type: 'float')]
    private $montantVerser=0;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updateAt;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'dettes')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    // private float $montantRestant=null
    
    private StatusDette $status = StatusDette::IMPAYE;
    
    public function __construct()
    {
        $this->creatAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        
    }
    public function getStatus(): StatusDette
    {
        if ($this->montantVerser != 0 && $this->montantVerser == $this->montant) {
            $this->status = StatusDette::PAYE;
        }
                  
        return $this->status;
    }
    public function isSoldee(): bool
    {
        return $this->getMontantVerser() >= $this->getMontant();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getmontantRestant(): ?int
    {
        return $this->montant - $this->montantVerser;
    }



    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getMontantVerser(): ?float
    {
        return $this->montantVerser;
    }
    //  public function setStatus(string $status): self
    // {
    //     // Valider que le statut est bien défini dans StatusDette
    //     if (!in_array($status, [StatusDette::PAYE, StatusDette::IMPAYE])) {
    //         throw new \InvalidArgumentException("Statut invalide pour Dette");
    //     }

    //     $this->status = $status;
    //     return $this;
    // }

    public function setMontantVerser(float $montantVerser): self
    {
        $this->montantVerser = $montantVerser;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
