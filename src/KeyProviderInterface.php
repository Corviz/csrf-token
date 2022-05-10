<?php

namespace Corviz\CsrfToken;

interface KeyProviderInterface
{
    /**
     * @return string|false
     */
    public function generateKey(): string|false;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage);
}