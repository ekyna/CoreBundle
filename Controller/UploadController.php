<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UploadController
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadController extends Controller
{
    /**
     * Download local file.
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws NotFoundHttpException
     */
    public function downloadAction(Request $request)
    {
        $key = $request->attributes->get('key');
        if (0 < strlen($key)) {
            $gfs = $this->get('gaufrette.local_upload_filesystem');
            if ($gfs->has($key)) {
                $file = 'gaufrette://local_upload/'.$key;
                return new BinaryFileResponse($file);
            }
        }

        throw new NotFoundHttpException('File not found');
    }
}
