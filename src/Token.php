<?php

namespace Corviz\CsrfToken;

use Corviz\CsrfToken\KeyProvider\DefaultCsrfKeyProvider;
use Corviz\CsrfToken\Storage\SessionStorage;

class Token
{
    /**
     * @var string
     */
    private static string $keyPrefix = 'csrf.token';

    /**
     * @var KeyProviderInterface
     */
    private KeyProviderInterface $keyProvider;

    /**
     * @var StorageInterface
     */
    private StorageInterface $storage;

    /**
     * @param string $keyPrefix
     */
    public static function setKeyPrefix(string $keyPrefix): void
    {
        self::$keyPrefix = $keyPrefix;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    /**
     * @return KeyProviderInterface
     */
    public function getKeyProvider(): KeyProviderInterface
    {
        return $this->keyProvider;
    }

    /**
     * @param KeyProviderInterface $keyProvider
     */
    public function setKeyProvider(KeyProviderInterface $keyProvider): void
    {
        $this->keyProvider = $keyProvider;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    public function generate(string $identifier = ''): string
    {
        $key = $this->getKeyProvider()->generateKey();
        $token = hash_hmac('sha256', $identifier, $key);

        $index = $this->createTokenIndex($identifier);
        $this->storage->set($index, $token);

        return $token;
    }

    /**
     * @param string $token
     * @param string $identifier
     *
     * @return bool
     */
    public function verify(string $token, string $identifier = ''): bool
    {
        $index = $this->createTokenIndex($identifier);

        if (!$this->getStorage()->isset($index)) {
            return false;
        }

        return hash_equals($this->getStorage()->get($index), $token);
    }

    /**
     * @param StorageInterface|null $storage
     * @param KeyProviderInterface|null $keyProvider
     */
    public function __construct(StorageInterface $storage = null, KeyProviderInterface $keyProvider = null)
    {
        $this->setStorage($storage ?? new SessionStorage());
        $this->setKeyProvider($keyProvider ?? new DefaultCsrfKeyProvider($this->getStorage()));
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    private function createTokenIndex(string $identifier): string
    {
        return self::$keyPrefix. ($identifier ? ".$identifier" : '');
    }
}
