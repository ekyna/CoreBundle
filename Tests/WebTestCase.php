<?php

namespace Ekyna\Bundle\CoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;

/**
 * Class WebTestCase
 * @package Ekyna\Bundle\CoreBundle\Tests
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class WebTestCase extends BaseTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client = null;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->client);
    }

    /**
     * Returns the router.
     *
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getRouter()
    {
        return $this->client->getContainer()->get('router');
    }

    /**
     * Generates a path.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function generatePath($route, $params = array())
    {
        return $this->getRouter()->generate($route, $params);
    }
}
