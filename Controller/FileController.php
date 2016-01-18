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
        // https://www.tinymce.com/docs/advanced/handle-async-image-uploads/

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = current($request->files->all());

        // Check file
        if (!$file->isValid()) {
            throw new \Exception('Invalid file.');
        }

        // Open uploaded file
        if (false === $stream = fopen($file->getRealPath(), 'r+')) {
            throw new \Exception('Failed to open file.');
        }

        // Verify extension
        // TODO check $mimeType = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, array("gif", "jpg", "png"))) {
            throw new \Exception('Invalid extension.');
        }

        // New file name
        $filename = strtolower(md5(time().uniqid())).'.'.$extension;

        // Write new file
        $fs = $this->get('local_tinymce_filesystem');
        if (!$fs->writeStream($filename, $stream)) {
            throw new \Exception('Failed to write file.');
        }
        if (is_resource($stream)) {
            fclose($stream);
        }

        return new JsonResponse([
            'location' => '/tinymce/' . $filename,
        ]);
    }
}
