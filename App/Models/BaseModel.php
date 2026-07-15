<?php
namespace App\Models;

use PDO;

/**
 * Classe mère de tous les modèles.
 * Fournit une connexion PDO partagée et quelques helpers CRUD.
 */
abstract class BaseModel
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct(?PDO $db = null)
    {
        if ($db === null) {
            require_once __DIR__ . '/../../config/database.php';
            $db = \Database::getConnection();
        }
        $this->db = $db;
    }

    public function all(string $orderBy = ''): array
    {
        $sql = "SELECT * FROM `{$this->table}`" . ($orderBy ? " ORDER BY {$orderBy}" : '');
        return $this->db->query($sql)->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function where(string $column, $value): array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = :v");
        $stmt->execute([':v' => $value]);
        return $stmt->fetchAll();
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`" . ($where ? " WHERE {$where}" : '');
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function insert(array $data): int
    {
        $cols = array_keys($data);
        $ph   = array_map(fn($c) => ':' . $c, $cols);
        $sql  = "INSERT INTO `{$this->table}` (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', $ph) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_combine($ph, array_values($data)));
        return (int) $this->db->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $set = implode(', ', array_map(fn($c) => "`$c` = :$c", array_keys($data)));
        $sql = "UPDATE `{$this->table}` SET {$set} WHERE `{$this->primaryKey}` = :__id";
        $stmt = $this->db->prepare($sql);
        $data['__id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute([':id' => $id]);
    }
}
