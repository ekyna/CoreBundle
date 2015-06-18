<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class FormController
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormController extends Controller
{
    /**
     * Returns the form plugins configuration.
     *
     * @return Response
     */
    public function configAction()
    {
        $response = new Response(
            json_encode($this->container->getParameter('ekyna_core.form_js'))
        );

        $response
            ->setPublic()
            ->setMaxAge(3600*6)
            ->setSharedMaxAge(3600*6)
            ->headers->add(array('Content-Type' => 'application/json'))
        ;

        return $response;
    }
}