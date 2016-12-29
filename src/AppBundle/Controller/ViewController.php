<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ViewController extends Controller
{
    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/", name="index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('view/index.html.twig', $this->getViewContext([
            'title' => 'Login',
        ]));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/demo", name="demo")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demoAction(Request $request)
    {
        return $this->render('view/demo.html.twig', $this->getViewContext([
            'title' => 'Demo',
        ]));
    }

    /**
     * @param array $custom
     * @return array
     */
    public function getViewContext($custom = [])
    {
        return array_merge([
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'title' => 'TODO',
            'description' => 'TODO: description',
            'navigation' => $this->getNavigation(),
        ], $custom);
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return [
            [
                'href' => $this->getSafeUrl('/demo'),
                'icon' => 'face',
                'title' => 'Demo',
            ],
            [
                'href' => $this->getSafeUrl('/'),
                'icon' => 'vpn_key',
                'title' => 'Login',
            ],
            [
                'href' => $this->getSafeUrl('/favorites'),
                'icon' => 'stars',
                'title' => 'Favorites',
            ],
            [
                'href' => $this->getSafeUrl('/subscriptions'),
                'icon' => 'subscriptions',
                'title' => 'Subscriptions',
            ],
            [
                'href' => $this->getSafeUrl('/videos'),
                'icon' => 'movie',
                'title' => 'Videos',
            ],
            [
                'href' => 'https://www.youtube.com',
                'target' => '_blank',
                'icon' => 'play_circle_filled',
                'title' => 'YouTube',
            ],
        ];
    }

    /**
     * @param string $url
     * @return string
     */
    public function getSafeUrl($url)
    {
        try {
            return $this->generateUrl($url);
        } catch (\Exception $ex) {
            if ($this->container->get('kernel')->isDebug()) {
                $url = '/app_dev.php' . $url;
            }
            return $url;
        }
    }
}
