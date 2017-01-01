<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use YouTubeBundle\Handler\YouTubeHandler;
use YouTubeBundle\Model\GoogleApiCredentials;

class GoogleOAuthController extends BaseController
{
    const SESSION_KEY_GOOGLE_SESSION = 'session_google';

    const ACCESS_TOKEN_FAKE_TOKEN = 'FAKE';

    /**
     * @return GoogleApiCredentials
     */
    protected function getCredentials()
    {
        $credentials = new GoogleApiCredentials();

        $credentials->setGoogleApiApplicationName($this->getParameter('google_api_application_name'));
        $credentials->setGoogleApiDeveloperKey($this->getParameter('google_api_developer_key'));
        $credentials->setGoogleApiOauth2JsonArray($this->getParameter('google_api_oauth2_json'));

        return $credentials;
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/oauth/google/auth", name="oauth_google_auth")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAuthenticationCodeAction()
    {
        if ($this->isLocal()) {
            $this->setAccessToken([
                'access_token' => self::ACCESS_TOKEN_FAKE_TOKEN,
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'created' => time(),
            ]);

            return $this->redirectToRoute('login');
        }

        $handler = new YouTubeHandler($this->getCredentials());

        // Send the user to complete their part of the OAuth
        return $this->redirect($handler->createAuthUrl());
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/oauth/google/redirect", name="oauth_google_redirect")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAccessCodeRedirectAction(Request $request)
    {
        $code = $request->query->get('code');

        if ($code) {
            $handler = new YouTubeHandler($this->getCredentials());

            $accessToken = $handler->handleRedirectionCode($code);

            $this->setAccessToken($accessToken);

            return $this->redirectToRoute('login');
        } else {
            $error = $request->query->get('error');

            $errorMessage = 'An error occurred';

            $logger = $this->get('logger');

            if (is_array($error)) {
                $logger->error($errorMessage, $error);
            } else {
                $logger->error($errorMessage);
            }

            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/oauth/google/accessToken", name="oauth_google_access_token")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAccessTokenAction()
    {
        return $this->json($this->getGoogleSession());
    }
}