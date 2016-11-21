<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model;

/**
 * Class AbstractUpload
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractUpload implements Model\UploadableInterface
{
    use Model\UploadableTrait;
}
