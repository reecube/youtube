<?php

namespace AppBundle\Controller;

use AppBundle\Enum\Access;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Enum\Pages;

abstract class BaseController extends Controller
{
    /**
     * @return array|null
     */
    public function getGoogleSession()
    {
        return $this->get('session')->get(GoogleOAuthController::SESSION_KEY_GOOGLE_SESSION);
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return $_SERVER['HTTP_HOST'] === 'youtube.reecube.local';
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
        $parsedPages = $this->getParsedPages();

        return array_merge([
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'locale' => $request->getLocale(),
            'languages' => $this->parseLanguages($this->getLanguages()),
            'description' => 'TODO: description',
            'navigation' => $parsedPages,
            'hasDrawer' => count($parsedPages) > 0,
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
     * @return array
     */
    public function getParsedPages()
    {
        $userAccess = $this->getUserAccess();

        $pages = [];

        foreach (Pages::PAGES as &$page) {
            if (Access::hasAccess($page[Pages::KEY_ACCESS], $userAccess)) {
                $pages[] = $this->parsePage($page);
            }
        }

        return $pages;
    }

    /**
     * @param int $id
     * @param mixed $default
     * @return array
     */
    public function getParsedPage($id, $default = null)
    {
        $pages = Pages::PAGES;

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
        if (!isset($page[Pages::KEY_IS_LINK]) || !$page[Pages::KEY_IS_LINK]) {
            $page[Pages::KEY_HREF] = $this->getSafeUrl($page[Pages::KEY_HREF]);
        }

        $page[Pages::KEY_TITLE] = $this->get('translator')->trans($page[Pages::KEY_TITLE]);

        return $page;
    }

    /**
     * @return int
     */
    public function getUserAccess()
    {
        $googleSession = $this->getGoogleSession();

        if ($googleSession === null) {
            return Access::ACCESS_GUEST;
        }

        return Access::ACCESS_USER;
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
