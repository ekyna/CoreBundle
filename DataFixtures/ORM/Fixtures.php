<?php

namespace Ekyna\Bundle\CoreBundle\DataFixtures\ORM;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Fixtures
 * @package Ekyna\Bundle\CoreBundle\DataFixtures\ORM
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class Fixtures
{
    /**
     * @var \Faker\Generator[]
     */
    static private $fakers;

    /**
     * @var PhoneNumberUtil
     */
    static private $phoneUtil;


    /**
     * Returns the faker generator for the given locale.
     *
     * @param string $locale
     *
     * @return \Faker\Generator
     */
    public static function getFaker(string $locale = \Faker\Factory::DEFAULT_LOCALE): \Faker\Generator
    {
        if (isset(self::$fakers[$locale])) {
            return self::$fakers[$locale];
        }

        return self::$fakers[$locale] = \Faker\Factory::create($locale);
    }

    /**
     * Returns the phone number utils.
     *
     * @return PhoneNumberUtil
     */
    public static function getPhoneUtil(): PhoneNumberUtil
    {
        if (self::$phoneUtil) {
            return self::$phoneUtil;
        }

        return self::$phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Fake upload from the given source file.
     *
     * @param string $source
     *
     * @return UploadedFile
     */
    public static function uploadFile($source): UploadedFile
    {
        if (!is_file($source)) {
            throw new \InvalidArgumentException(sprintf('Source file %s not found.', $source));
        }

        $fileName = pathinfo($source, PATHINFO_BASENAME);
        $target = sys_get_temp_dir() . '/' . uniqid() . '.' . pathinfo($source, PATHINFO_EXTENSION);

        if (!copy($source, $target)) {
            throw new \RuntimeException(sprintf('Failed to copy %s file.', $fileName));
        }

        return new UploadedFile($target, $fileName, null, null, null, true); // Last arg fakes the upload test
    }
}
