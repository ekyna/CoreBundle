<?php

namespace Ekyna\Bundle\CoreBundle\Event;

/**
 * Class HttpCacheEvents
 * @package Ekyna\Bundle\CoreBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
final class HttpCacheEvents
{
    const TAG_RESPONSE = 'ekyna_core.http_cache.event.tag_response';
    const INVALIDATE_TAG = 'ekyna_core.http_cache.event.invalidate_tag';
}