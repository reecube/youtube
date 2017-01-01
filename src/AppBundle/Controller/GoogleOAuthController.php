<?php

namespace AppBundle\Controller;

use AppBundle\Manager\GoogleManager;
use Symfony\Component\HttpFoundation\Request;

class GoogleOAuthController extends BaseController
{
    const ACCESS_TOKEN_FAKE_TOKEN = 'FAKE';
    const SESSION_KEY_GOOGLE_SESSION = 'session_google';

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/oauth/google/auth", name="oauth_google_auth")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAuthenticationCodeAction()
    {
        if (true || $this->isLocal()) {
            $this->setAccessToken([
                'access_token' => self::ACCESS_TOKEN_FAKE_TOKEN,
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'created' => time(),
            ]);

            return $this->redirectToRoute('login');
        }

        $client = $this->container->get('happyr.google.api.client');

        // Determine the level of access your application needs
        $client->getGoogleClient()->setScopes(GoogleManager::ACCESS_SCOPE);

        // Send the user to complete their part of the OAuth
        return $this->redirect($client->createAuthUrl());
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
            $client = $this->container->get('happyr.google.api.client');
            $client->getGoogleClient()->setScopes(GoogleManager::ACCESS_SCOPE);
            $client->authenticate($code);

            $this->setAccessToken($client->getGoogleClient()->getAccessToken());

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