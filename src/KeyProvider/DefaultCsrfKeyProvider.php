<?php

namespace Corviz\CsrfToken\KeyProvider;

use Corviz\CsrfToken\KeyProviderInterface;
use Corviz\CsrfToken\StorageInterface;

class DefaultCsrfKeyProvider implements KeyProviderInterface
{
    /**
     * @var int
     */
    private static int $keyLength = 32;

    /**
     * @var string
     */
    private static string $keyIndex = 'csrf.key';

    /**
     * @param int $keyLength
     */
    public static function setKeyLength(int $keyLength): void
    {
        self::$keyLength = $keyLength;
    }

    /**
     * @param string $keyIndex
     */
    public static function setKeyIndex(string $keyIndex): void
    {
        self::$keyIndex = $keyIndex;
    }

    /**
     * @var StorageInterface
     */
    private StorageInterface $storage;

    /**
     * @inheritDoc
     */
    public function generateKey(): string|false
    {
        if (!$this->storage->isset(self::$keyIndex)) {
            $this->storage->set(self::$keyIndex, bin2hex(random_bytes(self::$keyLength)));
        }

        return $this->storage->get(self::$keyIndex);
    }

    /**
     * @inheritDoc
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
}