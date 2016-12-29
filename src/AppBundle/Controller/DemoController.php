<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/", name="index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('demo/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'title' => 'Home',
            'description' => 'TODO: description',
            'navigation' => [
                [
                    'href' => $this->getSafeUrl('/'),
                    'icon' => 'home',
                    'title' => 'Home',
                ],
                [
                    'href' => $this->getSafeUrl('/inbox'),
                    'icon' => 'inbox',
                    'title' => 'Inbox',
                ],
                [
                    'href' => $this->getSafeUrl('/delete'),
                    'icon' => 'delete',
                    'title' => 'Trash',
                ],
                [
                    'href' => $this->getSafeUrl('/report'),
                    'icon' => 'report',
                    'title' => 'Spam',
                ],
            ],
        ]);
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
