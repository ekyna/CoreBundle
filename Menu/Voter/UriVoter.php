<?php

namespace Ekyna\Bundle\CoreBundle\Menu\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UriVoter
 * @package Ekyna\Bundle\CoreBundle\Menu\Voter
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UriVoter implements VoterInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $scriptName;

    /*public function __construct(Request $request = null)
    {
        $this->request = $request;
    }*/

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function matchItem(ItemInterface $item)
    {
        if (null === $this->request) {
            return null;
        }

        $uri = $item->getUri();
        if (0 < strlen($scriptName = $this->request->getScriptName())) {
            if (0 === strpos($uri, $this->request->getScriptName())) {
                $uri = substr($uri, strlen($this->request->getScriptName()));
            }
        }

        if (1 >= strlen($uri)) {
            return null;
        }

        if (preg_match(sprintf('#^%s#', $uri), $this->request->getPathInfo())) {
            return true;
        }

        return null;
    }
}
