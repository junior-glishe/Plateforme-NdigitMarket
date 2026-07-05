<?php
namespace App\Models;

class Seller extends BaseModel
{
    protected string $table = 'demandes_vendeur';
    protected string $primaryKey = 'id';

    public function pending(): array
    {
        return $this->where('statut', 'en_attente');
    }
}
