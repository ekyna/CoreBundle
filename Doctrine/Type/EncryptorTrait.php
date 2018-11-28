<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ekyna\Bundle\CoreBundle\Service\Encryptor\EncryptorInterface;

/**
 * Trait EncryptorTrait
 * @package Ekyna\Bundle\CoreBundle\Doctrine\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
trait EncryptorTrait
{
    /**
     * @var EncryptorInterface
     */
    protected $encryptor;


    /**
     * Returns the encryptor.
     *
     * @param AbstractPlatform $platform
     *
     * @return EncryptorInterface
     */
    protected function getEncryptor(AbstractPlatform $platform)
    {
        if ($this->encryptor) {
            return $this->encryptor;
        }

        $listeners = $platform->getEventManager()->getListeners('getEncryptor');
        $listener = array_shift($listeners);

        return $this->encryptor = $listener->getEncryptor();
    }
}
