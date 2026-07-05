<?php
namespace App\Models;

class Setting extends BaseModel
{
    protected string $table = 'parametres';
    protected string $primaryKey = 'id';

    public function get(string $key, $default = null)
    {
        $stmt = $this->db->prepare("SELECT valeur FROM parametres WHERE cle = :k LIMIT 1");
        $stmt->execute([':k' => $key]);
        $v = $stmt->fetchColumn();
        return $v === false ? $default : $v;
    }
}
