<?php
namespace App\Models;

class Product extends BaseModel
{
    protected string $table = 'produits';
    protected string $primaryKey = 'id';

    public function byStatus(string $status): array
    {
        return $this->where('statut', $status);
    }

    public function topSelling(int $limit = 5): array
    {
        $sql = "SELECT p.*, COUNT(c.a) AS nb_ventes
                FROM produits p
                LEFT JOIN commande c ON c.id_article = p.id
                GROUP BY p.id
                ORDER BY nb_ventes DESC
                LIMIT " . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }
}
