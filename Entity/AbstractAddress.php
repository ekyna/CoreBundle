<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\AddressInterface;

/**
 * Class AbstractAddress
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractAddress implements AddressInterface
{
    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $supplement;

    /**
     * @var string
     */
    protected $postalCode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country; // = 'FR'; // TODO configurable default country (through repo::createNew)

    /**
     * @var string
     */
    protected $state;


    /**
     * {@inheritdoc}
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function setSupplement($supplement)
    {
        $this->supplement = $supplement;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupplement()
    {
        return $this->supplement;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the country.
     *
     * @param string $country
     * @return AbstractAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Returns the country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the state.
     *
     * @param string $state
     * @return AbstractAddress
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Returns the state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
