<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model;

/**
 * Class Upload
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Upload implements Model\UploadableInterface
{
    use Model\UploadableTrait;
}
