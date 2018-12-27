<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;

/**
 * Class FormController
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FormController extends Controller
{
    private $locales = array(
        'bn' => 'bn_BD',
        'bg' => 'bg_BG',
        'cn' => 'zh_CN',
        'fr' => 'fr_FR',
        'hu' => 'hu_HU',
        'il' => 'he_IL',
        'is' => 'is_IS',
        'sl' => 'sl_SI',
        'tr' => 'tr_TR',
        'tw' => 'zh_TW',
        'uk' => 'uk_UA'
    );

    /**
     * Returns the form plugins configuration.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function pluginsAction(Request $request)
    {
        $this->preserveFlashes($request);

        $response = new JsonResponse($this->container->getParameter('ekyna_core.form_js'));

        $response
            ->setPublic()
            ->setMaxAge(3600 * 24 * 30)
            ->setSharedMaxAge(3600 * 24 * 30);;

        return $response;
    }

    /**
     * Returns the tinymce configuration.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function tinymceAction(Request $request)
    {
        $this->preserveFlashes($request);

        $response = new JsonResponse($this->buildTinymceConfig());

        $response
            ->setPublic()
            ->setMaxAge(3600 * 24 * 30)
            ->setSharedMaxAge(3600 * 24 * 30);

        return $response;
    }

    /**
     * Builds the tinymce config.
     *
     * @return string
     */
    private function buildTinymceConfig()
    {
        $config = $this->getParameter('ekyna_core.config.tinymce');

        // Get local button's image
        /*foreach ($config['tinymce_buttons'] as &$customButton) {
            if ($customButton['image']) {
                $customButton['image'] = $this->getAssetsUrl($customButton['image']);
            } else {
                unset($customButton['image']);
            }

            if ($customButton['icon']) {
                $customButton['icon'] = $this->getAssetsUrl($customButton['icon']);
            } else {
                unset($customButton['icon']);
            }
        }*/

        // Update URL to external plugins
        /*foreach ($config['external_plugins'] as &$extPlugin) {
            $extPlugin['url'] = $this->getAssetsUrl($extPlugin['url']);
        }*/

        // If the language is not set in the config...
        if (!isset($config['language']) || empty($config['language'])) {
            // get it from the request
            $config['language'] = $this->get('request_stack')->getCurrentRequest()->getLocale();
        }

        $config['language'] = $this->getLanguage($config['language']);

        $langDirectory = __DIR__ . '/../Resources/public/lib/tinymce/langs/';

        // A language code coming from the locale may not match an existing language file
        if (!file_exists($langDirectory . $config['language'] . '.js')) {
            unset($config['language']);
        }

        if (isset($config['language']) && $config['language']) {
            $languageUrl = $this->getAssetsUrl(
                '/bundles/ekynacore/lib/tinymce/langs/' . $config['language'] . '.js'
            );
            // TinyMCE does not allow to set different languages to each instance
            foreach ($config['theme'] as $themeName => $themeOptions) {
                $config['theme'][$themeName]['language'] = $config['language'];
                $config['theme'][$themeName]['language_url'] = $languageUrl;
            }
            $config['language_url'] = $languageUrl;
        }

        if (isset($config['theme']) && $config['theme']) {
            // Parse the content_css of each theme so we can use 'asset[path/to/asset]' in there
            foreach ($config['theme'] as $themeName => $themeOptions) {
                if (isset($themeOptions['content_css'])) {
                    // As there may be multiple CSS Files specified we need to parse each of them individually
                    $cssFiles = is_array($themeOptions['content_css'])
                        ? $themeOptions['content_css']
                        : explode(',', $themeOptions['content_css']);
                    foreach ($cssFiles as $idx => $file) {
                        // we trim to be sure we get the file without spaces.
                        $cssFiles[$idx] = $this->getAssetsUrl(trim($file));
                    }
                    $config['theme'][$themeName]['content_css'] = array_values($cssFiles);
                }
            }
        }

        return $config;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function getLanguage($locale)
    {
        return isset($this->locales[$locale])
            ? $this->locales[$locale]
            : $locale;
    }

    /**
     * Get url from config string
     *
     * @param string $inputUrl
     *
     * @return string
     */
    protected function getAssetsUrl($inputUrl)
    {
        $url = preg_replace('/^asset\[(.+)\]$/i', '$1', $inputUrl);

        if ($inputUrl !== $url) {
            return $this->getUrl($url);
        }

        return $inputUrl;
    }

    /**
     * Returns the asset package url.
     *
     * @param string $url
     *
     * @return string
     */
    protected function getUrl($url)
    {
        return $this->get('assets.packages')->getUrl($url);
    }

    /**
     * Keep flashes in the session.
     *
     * @param Request $request
     */
    private function preserveFlashes(Request $request)
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $request->getSession();
        if ($request->hasPreviousSession() && $session->getFlashBag() instanceof AutoExpireFlashBag) {
            // keep current flashes for one more request if using AutoExpireFlashBag
            $session->getFlashBag()->setAll($session->getFlashBag()->peekAll());
        }
    }
}
