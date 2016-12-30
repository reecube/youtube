<?php

namespace AppBundle\Controller;

use AppBundle\Enum\Access;
use AppBundle\Enum\Languages;
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
     * @param array $page
     * @param array|null $custom
     * @return array
     */
    public function getViewContext(Request $request, array $page, $custom = null)
    {
        $parsedPages = $this->getParsedPages();

        $context = [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'locale' => $request->getLocale(),
            'languages' => $this->getParsedLanguages(),
            'description' => 'TODO: description',
            'page' => $page,
            'navigation' => $parsedPages,
            'hasDrawer' => count($parsedPages) > 0,
        ];

        if ($custom === null) {
            return $context;
        }

        return array_merge($context, $custom);
    }

    /**
     * @return array
     */
    public function getParsedLanguages()
    {
        $languages = [];

        foreach (Languages::LANGUAGES as $langId => $language) {
            $language[Languages::KEY_URL] = $this->generateUrl('language', [
                '_locale' => $language[Languages::KEY_LOCALE],
            ]);

            $languages[$langId] = $language;
        }

        return $languages;
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
     * @param array $page
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkAccessOrRedirect(array $page)
    {
        $userAccess = $this->getUserAccess();

        if (!Access::hasAccess($page[Pages::KEY_ACCESS], $userAccess)) {
            return $this->redirectToRoute('login');
        }

        return null;
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
