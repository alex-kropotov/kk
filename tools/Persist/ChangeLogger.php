<?php
declare(strict_types=1);

namespace Tools\Persist;

use DateTime;

class ChangeLogger
{
    /**
     * Логгирует изменения данных.
     *
     * @param string $tableName   Название таблицы
     * @param int|null $recordId  ID изменяемой записи
     * @param string $action      insert | update | delete
     * @param array $changes      Массив вида ['field_name' => [old, new]]
     * @param string|null $changedBy Кто совершил изменение
     */
    public function log(string $tableName, ?int $recordId, string $action, array $changes, ?string $changedBy = null): void
    {
        $pdo = Container::pdo()->getConn();

        $stmt = $pdo->prepare("
            INSERT INTO change_log 
            (table_name, record_id, action, field_name, old_value, new_value, changed_at, changed_by)
            VALUES 
            (:table_name, :record_id, :action, :field_name, :old_value, :new_value, :changed_at, :changed_by)
        ");

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $user = $changedBy ?? ($_SESSION['user'] ?? 'system');

        foreach ($changes as $field => [$old, $new]) {
            $stmt->execute([
                'table_name'  => $tableName,
                'record_id'   => $recordId,
                'action'      => $action,
                'field_name'  => $field,
                'old_value'   => (string) $old,
                'new_value'   => (string) $new,
                'changed_at'  => $now,
                'changed_by'  => $user,
            ]);
        }
    }
}
