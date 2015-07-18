<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface AddressInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface AddressInterface
{
    /**
     * Set street
     *
     * @param string $street
     * @return AddressInterface|$this
     */
    public function setStreet($street);

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet();

    /**
     * Set supplement
     *
     * @param string $supplement
     * @return AddressInterface|$this
     */
    public function setSupplement($supplement);

    /**
     * Get supplement
     *
     * @return string
     */
    public function getSupplement();

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return AddressInterface|$this
     */
    public function setPostalCode($postalCode);

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode();

    /**
     * Set city
     *
     * @param string $city
     * @return AddressInterface|$this
     */
    public function setCity($city);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();

    /**
     * Sets the country.
     *
     * @param string $country
     * @return AddressInterface|$this
     */
    public function setCountry($country);

    /**
     * Returns the country.
     *
     * @return string
     */
    public function getCountry();

    /**
     * Sets the state.
     *
     * @param string $state
     * @return AddressInterface|$this
     */
    public function setState($state);

    /**
     * Returns the state.
     *
     * @return string
     */
    public function getState();
}