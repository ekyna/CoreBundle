<?php

namespace Ekyna\Bundle\CoreBundle\Redirection;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractProvider
 * @package Ekyna\Bundle\CoreBundle\Redirection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return 0 < strlen($request->getPathInfo());
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
