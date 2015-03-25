<?php

namespace Ekyna\Bundle\CoreBundle\Redirection;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProviderInterface
 * @package Ekyna\Bundle\CoreBundle\Redirection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ProviderInterface
{
    /**
     * Returns the RedirectResponse or the path to redirect to, or false.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|string|false
     */
    public function redirect(Request $request);

    /**
     * Returns whether this provider supports the request.
     *
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request);

    /**
     * Returns the provider priority.
     *
     * @return int
     */
    public function getPriority();

    /**
     * Returns the provider name.
     *
     * @return string
     */
    public function getName();
}
