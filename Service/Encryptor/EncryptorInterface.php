<?php

namespace Ekyna\Bundle\CoreBundle\Service\Encryptor;

/**
 * Interface EncryptorInterface
 * @package Ekyna\Bundle\CoreBundle\Service\Encryptor
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface EncryptorInterface
{
    /**
     * Encrypts the given data.
     *
     * @param string $data Plain text to encrypt
     *
     * @return string Encrypted text
     */
    public function encrypt($data);

    /**
     * Decrypts the given data.
     *
     * @param string $data Encrypted text
     *
     * @return string Plain text
     */
    public function decrypt($data);
}
