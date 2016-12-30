<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Enum\ViewNavigation;

abstract class BaseController extends Controller
{
    /**
     * @param Request $request
     * @return array|null
     */
    public function getGoogleSession(Request $request)
    {
        $session = $request->getSession();

        return $session->get(GoogleOAuthController::SESSION_KEY_GOOGLE_SESSION);
    }

    /**
     * @return bool
     */
    public function isDevEnv()
    {
        return $this->container->get('kernel')->getEnvironment() === 'dev';
    }

    /**
     * @param Request $request
     * @param array $custom
     * @return array
     */
    public function getViewContext(Request $request, $custom = [])
    {
        $navigation = $this->getNavigation();

        return array_merge([
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'locale' => $request->getLocale(),
            'languages' => $this->parseLanguages($this->getLanguages()),
            'description' => 'TODO: description',
            'navigation' => $navigation,
            'hasDrawer' => count($navigation) > 0,
        ], $custom);
    }

    /**
     * @param array $languages
     * @return array
     */
    public function parseLanguages(array $languages)
    {
        foreach ($languages as $languageIdx => $language) {
            $language['url'] = $this->generateUrl('language', [
                '_locale' => $language['locale'],
            ]);

            $languages[$languageIdx] = $language;
        }

        return $languages;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return [
            [
                'locale' => 'de',
                'title' => 'Deutsch',
            ],
            [
                'locale' => 'fr',
                'title' => 'Français',
            ],
            [
                'locale' => 'en',
                'title' => 'English',
            ],
        ];
    }

    /**
     * @param int $id
     * @param mixed $default
     * @return array
     */
    public function getParsedPage($id, $default = null)
    {
        $pages = ViewNavigation::PAGES;

        if (!isset($pages[$id])) {
            return $default;
        }

        return $this->parsePage($pages[$id]);
    }

    /**
     * @param array $page
     * @return array
     */
    protected function parsePage(array &$page)
    {
        if (!isset($page['isLink']) || !$page['isLink']) {
            $page['href'] = $this->getSafeUrl($page['href']);
        }

        $page['title'] = $this->get('translator')->trans($page['title']);

        return $page;
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        // FIXME: check here for user permissions
        if (!$this->isDevEnv()) {
            return [];
        }

        $pages = ViewNavigation::PAGES;

        foreach ($pages as $pageId => &$page) {
            $pages[$pageId] = $this->parsePage($page);
        }

        return $pages;
    }

    /**
     * @param string $url
     * @param bool $absoluteUrl
     * @return string
     */
    public function getSafeUrl($url, $absoluteUrl = true)
    {
        if ($absoluteUrl && strpos($url, '/') !== 0) {
            return $url;
        }

        try {
            return $this->generateUrl($url);
        } catch (\Exception $ex) {

            if ($absoluteUrl && $this->isDevEnv()) {
                $url = '/app_dev.php' . $url;
            }

            return $url;
        }
    }
}
