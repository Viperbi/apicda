<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/categorie/{id}',
            requirements: ['id' => '\d+'],
        ),
        new GetCollection(
            uriTemplate: '/categories',
        ),
        new Post(
            uriTemplate: '/categorie',
            status: 201
        ),
        new Delete(
            uriTemplate: '/categorie/{id}',
            requirements: ['id' => '\d+'],
            status: 204
        ),
        new Put(
            uriTemplate: '/categorie/{id}',
            requirements: ['id' => '\d+'],
            status: 201
        ),
    ],
    order: ['id' => 'ASC', 'nom' => 'ASC'],
    paginationEnabled: true
)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
