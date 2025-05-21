<?php

namespace Tools\Services;

use Tools\Utils\NamedLog;

class SessionService
{
    private array $sessionData = [];
    private array $removedKeys = [];

    public function setSession(array $session): void
    {
        $this->sessionData = $session;
        $this->removedKeys = [];
    }

    public function getSession(): array
    {
        return $this->sessionData;
    }

    public function get(SessionKeyInterface $key, mixed $default = null): mixed
    {
        return $this->sessionData[$key->value()] ?? $default;
    }

    public function set(SessionKeyInterface $key, mixed $value): void
    {
        unset($this->removedKeys[$key->value()]);
        $this->sessionData[$key->value()] = $value;
    }

    public function unset(SessionKeyInterface $key): void
    {
        $keyStr = $key->value;
        if (array_key_exists($keyStr, $this->sessionData)) {
            $this->removedKeys[$keyStr] = $this->sessionData[$keyStr];
        }
        unset($this->sessionData[$keyStr]);
    }

    public function persist(): void
    {
        // Копируем данные из sessionData в глобальную сессию
        foreach ($this->sessionData as $key => $value) {
            $_SESSION[$key] = $value;
        }

        // Удаляем все ключи, которые были помечены для удаления
        foreach (array_keys($this->removedKeys) as $key) {
            unset($_SESSION[$key]);
        }
        session_write_close(); // Принудительно сохраняем
    }

    public function ensureSessionActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function reset(): void
    {
        $this->sessionData = [];
        $_SESSION = [];

        // Если нужно полное уничтожение сессии с удалением файла/данных:
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            session_start(); // Создаем новую чистую сессию
        }
    }

}
