<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Enum\ViewNavigation;

class ViewController extends Controller
{
    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/", name="index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('login');
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/demo", name="demo")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demoAction(Request $request)
    {
        return $this->render('view/demo.html.twig', $this->getViewContext([
            'page' => ViewNavigation::PAGES[ViewNavigation::ID_PAGE_DEMO],
        ]));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        return $this->render('view/login.html.twig', $this->getViewContext([
            'page' => ViewNavigation::PAGES[ViewNavigation::ID_PAGE_LOGIN],
        ]));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/favorites", name="favorites")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favoritesAction(Request $request)
    {
        return $this->render('view/favorites.html.twig', $this->getViewContext([
            'page' => ViewNavigation::PAGES[ViewNavigation::ID_PAGE_FAVORITES],
        ]));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/subscriptions", name="subscriptions")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscriptionsAction(Request $request)
    {
        return $this->render('view/subscriptions.html.twig', $this->getViewContext([
            'page' => ViewNavigation::PAGES[ViewNavigation::ID_PAGE_SUBSCRIPTIONS],
        ]));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/videos", name="videos")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function videosAction(Request $request)
    {
        return $this->render('view/videos.html.twig', $this->getViewContext([
            'page' => ViewNavigation::PAGES[ViewNavigation::ID_PAGE_VIDEOS],
        ]));
    }

    /**
     * @return bool
     */
    public function isDevEnv()
    {
        return $this->container->get('kernel')->getEnvironment() === 'dev';
    }

    /**
     * @param array $custom
     * @return array
     */
    public function getViewContext($custom = [])
    {
        $navigation = $this->getNavigation();

        return array_merge([
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'description' => 'TODO: description',
            'navigation' => $navigation,
            'hasDrawer' => count($navigation) > 0,
        ], $custom);
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
            if (!isset($page['isLink']) || !$page['isLink']) {
                $page['href'] = $this->getSafeUrl($page['href']);
            }

            $pages[$pageId] = $page;
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
