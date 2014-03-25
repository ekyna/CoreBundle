<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

interface ImageUploaderInterface
{
    public function upload(ImageInterface $image);
    public function remove(ImageInterface $image);
}
