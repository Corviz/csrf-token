<?php

namespace Corviz\CsrfToken\Storage;

use Corviz\CsrfToken\StorageInterface;
use Exception;

class SessionStorage implements StorageInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key];
    }

    /**
     * @inheritDoc
     */
    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function set(string $key, mixed $value): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new Exception('Session has not started!');
        }

        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
