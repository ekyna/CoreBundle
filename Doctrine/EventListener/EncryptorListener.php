<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\EventListener;

use Ekyna\Bundle\CoreBundle\Service\Encryptor\EncryptorInterface;

/**
 * Class EncryptorListener
 * @package Ekyna\Bundle\CoreBundle\Doctrine\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @see http://emanueleminotto.github.io/blog/service-injection-doctrine-dbal-type
 */
class EncryptorListener
{
    /**
     * @var EncryptorInterface
     */
    private $encryptor;


    /**
     * Constructor.
     *
     * @param EncryptorInterface $encryptor
     */
    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * Returns the encryptor.
     *
     * @return EncryptorInterface
     */
    public function getEncryptor()
    {
        return $this->encryptor;
    }
}
