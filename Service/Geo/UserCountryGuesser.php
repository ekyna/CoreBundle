<?php

namespace Ekyna\Bundle\CoreBundle\Service\Geo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class UserCountryGuesser
 * @package Ekyna\Bundle\CoreBundle\Service\Geo
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @TODO Caching
 */
class UserCountryGuesser
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $results;


    /**
     * Constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->results = [];
    }

    /**
     * Returns the user country iso code (alpha-2).
     *
     * @param string $default
     *
     * @return string|null
     */
    public function getUserCountry(string $default = 'US'): ?string
    {
        if (null === $request = $this->requestStack->getMasterRequest()) {
            return $default;
        }

        if (null === $ip = $request->getClientIp()) {
            return $default;
        }

        if (isset($this->results[$ip])) {
            return $this->results[$ip];
        }

        $client = $this->getClient();

        try {
            $response = $client->request('GET', 'https://ip2c.org/', [
                'query'   => ['ip' => $ip],
                'stream'  => true,
                'timeout' => 0.3,
            ]);
        } catch (GuzzleException $e) {
            return $default;
        }

        if (200 !== $response->getStatusCode()) {
            return $default;
        }

        $content = $response->getBody()->getContents();

        $result = explode(';', $content);
        if ('1' !== $result[0]) {
            return $default;
        }

        $code = $result[1];
        if (in_array($code, ['EU', 'ZZ'], true)) {
            $code = $default;
        }

        return $this->results[$ip] = $code;

        //list ($index, $iso2, $iso3, $name) = explode(';', $response->getBody()->getContents());
        //return $iso2;
    }

    /**
     * Returns the http client.
     *
     * @return Client
     */
    private function getClient(): Client
    {
        if ($this->client) {
            return $this->client;
        }

        return $this->client = new Client();
    }
}