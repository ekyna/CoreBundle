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
        $response = new Response();

        /*$response->setContent(json_encode(array(
            array('selector' => '.file-widget',               'name' => 'ekyna-form/file'),
            array('selector' => '.form-datetime-picker',      'name' => 'ekyna-form/datetime'),
            array('selector' => '.form-color-picker',         'name' => 'ekyna-form/color'),
            array('selector' => '.entity-widget',             'name' => 'ekyna-form/entity'),
            array('selector' => '.entity-search',             'name' => 'ekyna-form/entity-search'),
            array('selector' => '.tinymce',                   'name' => 'ekyna-form/tinymce'),
            array('selector' => '.ekyna-collection',          'name' => 'ekyna-form/collection'),
            array('selector' => 'select[data-parent-choice]', 'name' => 'ekyna-form/parent-choice'),
        )));*/
        $response->setContent(json_encode($this->container->getParameter('ekyna_core.form_js')));

        $response->headers->add(array('Content-Type' => 'application/json'));

        // TODO file cache
        $response
            ->setPublic()
            ->setMaxAge(3600)
        ;

        return $response;
    }
}