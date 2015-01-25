<?php

namespace Ekyna\Bundle\CoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use Symfony\Component\BrowserKit\Cookie;

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
     * Log the given email
     * @param string $email
     */
    protected function login($email = 'admin@example.org')
    {
        $container = $this->client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => $email));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $session->set('_security_' . $firewallName, serialize($container->get('security.context')->getToken()));
        $session->save();

        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
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