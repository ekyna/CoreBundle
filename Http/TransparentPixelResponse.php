<?php

namespace Ekyna\Bundle\CoreBundle\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransparentPixelResponse
 * @package Ekyna\Bundle\CoreBundle\Http
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TransparentPixelResponse extends Response
{
    /**
     * @var string
     */
    const IMAGE_CONTENT = 'R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==';

    /**
     * @var string
     */
    const CONTENT_TYPE = 'image/gif';

    /**
     * Constructor
     */
    public function __construct()
    {
        $content = base64_decode(self::IMAGE_CONTENT);

        parent::__construct($content);

        $this->headers->set('Content-Type', self::CONTENT_TYPE);

        $this->setPrivate();
        $this->headers->addCacheControlDirective('no-cache', true);
        $this->headers->addCacheControlDirective('must-revalidate', true);
    }
}
