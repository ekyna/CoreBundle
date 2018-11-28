<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

/**
 * Class EncryptedJsonArrayType
 * @package Ekyna\Bundle\CoreBundle\Doctrine\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EncryptedJsonType extends JsonType
{
    use EncryptorTrait;

    const NAME = 'encrypted_json';


    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $encoded = parent::convertToDatabaseValue($value, $platform);

        return $this->getEncryptor($platform)->encrypt($encoded);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decrypted = $this->getEncryptor($platform)->decrypt($value);

        return parent::convertToPHPValue($decrypted, $platform);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }
}
