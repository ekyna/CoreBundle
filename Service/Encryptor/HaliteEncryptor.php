<?php

namespace Ekyna\Bundle\CoreBundle\Service\Encryptor;

use ParagonIE\Halite\Alerts;
use ParagonIE\Halite\HiddenString;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto;

/**
 * Class HaliteEncryptor
 * @package Ekyna\Bundle\CoreBundle\Service\Encryptor
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class HaliteEncryptor implements EncryptorInterface
{
    /**
     * @var \ParagonIE\Halite\Symmetric\EncryptionKey
     */
    private $encryptionKey;

    /**
     * @var string
     */
    private $keyFile;


    /**
     * Constructor.
     *
     * @param string $keyFile
     */
    public function __construct(string $keyFile)
    {
        $this->encryptionKey = null;
        $this->keyFile = $keyFile;
    }

    /**
     * @inheritdoc
     */
    public function encrypt($data)
    {
        return Crypto::encrypt(new HiddenString($data), $this->getKey());
    }

    /**
     * @inheritdoc
     */
    public function decrypt($data)
    {
        return Crypto::decrypt($data, $this->getKey());
    }

    /**
     * Returns the encryption key.
     *
     * @return \ParagonIE\Halite\Symmetric\EncryptionKey|null
     *
     * @throws Alerts\CannotPerformOperation
     * @throws Alerts\InvalidKey
     */
    private function getKey()
    {
        if ($this->encryptionKey === null) {
            if (!is_dir($directory = dirname($this->keyFile))) {
                if (!mkdir($directory)) {
                    throw new Alerts\CannotPerformOperation('Cannot create directory: ' . $directory);
                }
            }

            try {
                $this->encryptionKey = KeyFactory::loadEncryptionKey($this->keyFile);
            } catch (Alerts\CannotPerformOperation $e) {
                $this->encryptionKey = KeyFactory::generateEncryptionKey();
                KeyFactory::save($this->encryptionKey, $this->keyFile);
            }
        }

        return $this->encryptionKey;
    }
}
