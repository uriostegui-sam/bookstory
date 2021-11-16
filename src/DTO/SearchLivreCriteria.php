<?php

declare(strict_types=1);

namespace App\DTO;

class SearchLivreCriteria
{
  public ?string $titre = null;
  public ?string $auteur = null;
  public ?string $revendeur = null;
  public ?string $categorie = null;
  public ?float $minPrix = null;
  public ?float $maxPrix = null;
  public ?int $page = 1;
  public ?int $limit = 25;
  public ?string $orderBy = 'dateMiseAJour';
  public ?string $direction = 'DESC';
}
