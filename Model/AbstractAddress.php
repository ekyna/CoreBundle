<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Class AbstractAddress
 * @package Ekyna\Bundle\CoreBundle\Model
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
}
