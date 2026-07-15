<?php
namespace App\Models;

class Subscription extends BaseModel
{
    protected string $table = 'abonnement';
    protected string $primaryKey = 'id_abonnement';

    public function active(): array
    {
        $sql = "SELECT * FROM abonnement WHERE date_fin > NOW()";
        return $this->db->query($sql)->fetchAll();
    }
}
