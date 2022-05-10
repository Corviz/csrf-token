<?php

namespace Corviz\CsrfToken;

interface StorageInterface
{
    /**
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     *
     * @param string $key
     *
     * @return bool
     */
    public function isset(string $key): bool;

    /**
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     *
     * @param string $key
     *
     * @return void
     */
    public function unset(string $key): void;
}
