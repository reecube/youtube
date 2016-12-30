<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Enum\Pages;

class ViewController extends BaseController
{
    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->redirectToRoute('login');
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/language/{_locale}", name="language",
     *     requirements={
     *         "_locale": "en|fr|de"
     *     })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function languageAction(Request $request)
    {
        $_locale = $request->attributes->get('_locale');

        return $this->json([
            'success' => $_locale === $request->getLocale(),
        ]);
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/demo", name="demo")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function demoAction(Request $request)
    {
        $page = $this->getParsedPage(Pages::ID_PAGE_DEMO);

        $result = $this->checkAccessOrRedirect($page);

        if ($result !== null) {
            return $result;
        }

        return $this->render('view/demo.html.twig', $this->getViewContext($request, $page));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $googleSession = $this->getGoogleSession();

        if ($googleSession !== null) {

            // TODO: change this to user setting or similar thing
            return $this->redirectToRoute('favorites');
        }

        $service = $request->get('service');

        $service = strtolower($service);

        if ($service === 'youtube') {
            return $this->redirectToRoute('oauth_google_auth');
        }

        $page = $this->getParsedPage(Pages::ID_PAGE_LOGIN);

        return $this->render('view/login.html.twig', $this->getViewContext($request, $page));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/logout", name="logout")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction()
    {
        $this->setAccessToken();

        return $this->redirectToRoute('login');
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/favorites", name="favorites")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favoritesAction(Request $request)
    {
        $page = $this->getParsedPage(Pages::ID_PAGE_FAVORITES);

        $result = $this->checkAccessOrRedirect($page);

        if ($result !== null) {
            return $result;
        }

        return $this->render('view/favorites.html.twig', $this->getViewContext($request, $page));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/subscriptions", name="subscriptions")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscriptionsAction(Request $request)
    {
        $page = $this->getParsedPage(Pages::ID_PAGE_SUBSCRIPTIONS);

        $result = $this->checkAccessOrRedirect($page);

        if ($result !== null) {
            return $result;
        }

        return $this->render('view/subscriptions.html.twig', $this->getViewContext($request, $page));
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/videos", name="videos")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function videosAction(Request $request)
    {
        $page = $this->getParsedPage(Pages::ID_PAGE_VIDEOS);

        $result = $this->checkAccessOrRedirect($page);

        if ($result !== null) {
            return $result;
        }

        return $this->render('view/videos.html.twig', $this->getViewContext($request, $page));
    }
}
