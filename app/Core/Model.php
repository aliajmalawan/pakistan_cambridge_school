<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table = '';

    public static function db(): PDO
    {
        return Database::pdo();
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        return Database::query('SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy)->fetchAll();
    }

    public static function where(string $condition, array $params = [], string $orderBy = 'id DESC', ?int $limit = null): array
    {
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE ' . $condition . ' ORDER BY ' . $orderBy;
        if ($limit !== null) {
            $sql .= ' LIMIT ' . $limit;
        }
        return Database::query($sql, $params)->fetchAll();
    }

    public static function first(string $condition, array $params = [], string $orderBy = 'id DESC'): ?array
    {
        $rows = static::where($condition, $params, $orderBy, 1);
        return $rows[0] ?? null;
    }

    public static function find(int $id): ?array
    {
        return static::first('id = ?', [$id]);
    }

    public static function findBy(string $column, string|int $value): ?array
    {
        return static::first($column . ' = ?', [$value]);
    }

    public static function create(array $data): int
    {
        $cols = array_keys($data);
        $sql = 'INSERT INTO ' . static::$table
            . ' (' . implode(', ', $cols) . ') VALUES (' . rtrim(str_repeat('?, ', count($cols)), ', ') . ')';
        Database::query($sql, array_values($data));
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($c) => $c . ' = ?', array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        Database::query('UPDATE ' . static::$table . ' SET ' . $set . ' WHERE id = ?', $params);
        return true;
    }

    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM ' . static::$table . ' WHERE id = ?', [$id]);
        return true;
    }

    public static function count(string $condition = '1', array $params = []): int
    {
        return (int) Database::query('SELECT COUNT(*) c FROM ' . static::$table . ' WHERE ' . $condition, $params)->fetch()['c'];
    }

    /**
     * @return array{items: array, total: int, page: int, pages: int, per: int}
     */
    public static function paginate(int $page = 1, int $per = PER_PAGE, string $condition = '1', array $params = [], string $orderBy = 'id DESC'): array
    {
        $page = max(1, $page);
        $total = static::count($condition, $params);
        $pages = max(1, (int) ceil($total / $per));
        $page = min($page, $pages);
        $offset = ($page - 1) * $per;
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE ' . $condition
            . ' ORDER BY ' . $orderBy . ' LIMIT ' . $per . ' OFFSET ' . $offset;
        return [
            'items' => Database::query($sql, $params)->fetchAll(),
            'total' => $total,
            'page'  => $page,
            'pages' => $pages,
            'per'   => $per,
        ];
    }
}
