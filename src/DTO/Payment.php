<?php

declare(strict_types=1);

namespace App\DTO;

use DateTime;

class Payment
{
  public ?string $titulaire = null;
  public ?string $email = null;
  public ?string $pays = null;
  public ?int $numCarte = null;
  public ?DateTime $expiration = null;
  public ?int $cryptogramme = null;
}
