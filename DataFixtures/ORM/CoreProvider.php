<?php

namespace Ekyna\Bundle\CoreBundle\DataFixtures\ORM;

use Ekyna\Bundle\CoreBundle\Model\FAIcons;
use libphonenumber\PhoneNumber;

/**
 * Class CoreProvider
 * @package Ekyna\Bundle\CoreBundle\DataFixtures\ORM
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CoreProvider
{
    /**
     * Returns a random font awesome icon.
     *
     * @return string
     */
    public function randomFAIcon()
    {
        return Fixtures::getFaker()->randomElement(FAIcons::getConstants());
    }

    /**
     * Generates a phone number.
     *
     * @param bool $mobile
     * @param string $locale
     *
     * @return PhoneNumber
     */
    public function generatePhoneNumber(
        bool $mobile = false,
        string $locale = \Faker\Factory::DEFAULT_LOCALE
    ): PhoneNumber {
        $number = str_replace(' ', '', Fixtures::getFaker($locale)->phoneNumber);
        if ($mobile && !preg_match('~^06~', $number)) {
            $number = '06' . substr($number, 2);
        }

        return Fixtures::getPhoneUtil()->parse($number, 'FR');
    }
}
