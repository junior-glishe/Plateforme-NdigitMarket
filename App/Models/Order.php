<?php
namespace App\Models;

class Order extends BaseModel
{
    protected string $table = 'commande';
    protected string $primaryKey = 'commande_id';

    public function recent(int $limit = 10): array
    {
        $sql = "SELECT * FROM commande ORDER BY date_commande DESC LIMIT " . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }

    public function totalRevenue(): float
    {
        $sql = "SELECT SUM(CAST(REPLACE(prix, ' ', '') AS DECIMAL(14,2))) FROM commande";
        return (float) $this->db->query($sql)->fetchColumn();
    }
}
