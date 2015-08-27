<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FileController
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FileController extends Controller
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
            $fs = $this->get('local_upload_filesystem');
            if ($fs->has($key)) {
                $file = $fs->get($key);

                // TODO 304 not modified

                $response = new StreamedResponse();

                // Set the headers
                $response->headers->set('Content-Type', $file->getMimetype());
                $response->headers->set('Content-Length', $file->getSize());

                // TODO http cache
                /*$this->setHttpCacheHeaders(
                    $lastModified,
                    md5($this->getCachePath() . $lastModified->getTimestamp()),
                    $this->maxAge
                );*/

                $response->setCallback(function () use ($file) {
                    fpassthru($file->readStream());
                });

                return $response;
            }
        }

        throw new NotFoundHttpException('File not found');
    }

    /**
     * Handle tinymce upload.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function tinymceUploadAction(Request $request)
    {
        // TODO check admin ?

        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $name = $request->request->get('name');
        $base64 = $request->request->get('data');

        $filename = md5(time().uniqid()).".jpg";
        $data = explode(',', $base64);
        if (2 != count($data)) {
            throw new \InvalidArgumentException('Invalid image data.');
        }

        $fs = $this->get('local_tinymce_filesystem');
        if (!$fs->put($filename, base64_decode($data[1]))) {
            throw new \Exception('Failed to create image.');
        }

        return new JsonResponse(array(
            'location' => '/tinymce/' . $filename,
        ));
    }
}
