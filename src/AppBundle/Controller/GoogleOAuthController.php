<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class GoogleOAuthController extends BaseController
{
    const SESSION_KEY_GOOGLE_SESSION = 'session_google';

    protected $accessScope = [
        \Google_Service_YouTube::YOUTUBE,
    ];

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/oauth/google/auth", name="oauth_google_auth")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAuthenticationCodeAction(Request $request)
    {
        if ($this->isDevEnv()) {
            if ($request->isMethod('POST')) {
                $atobjectString = $request->get('atobject');

                $accessToken = [
                    'access_token' => $request->get('access_token'),
                    'token_type' => $request->get('token_type'),
                    'expires_in' => $request->get('expires_in'),
                    'created' => $request->get('created'),
                ];

                if ($atobjectString !== null && is_string($atobjectString) && strlen($atobjectString) > 0) {
                    try {
                        $accessToken = json_decode($atobjectString, true);
                    } catch (\Exception $ex) {
                        $logger = $this->get('logger');

                        $logger->addError($ex->getMessage());
                    }
                }

                $this->storeAccessToken($request, $accessToken);

                return $this->redirectToRoute('login');
            }

            $page = [
                'href' => '/oauth/google/auth',
                'icon' => 'vpn_key',
                'title' => 'title_oauth_google_auth',
                'isLink' => false,
            ];

            return $this->render('googleoauth/auth.html.twig', $this->getViewContext($request, [
                'page' => $this->parsePage($page),
                'form' => [
                    'atobject' => '',
                    'access_token' => '',
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'created' => '',
                ],
            ]));
        }

        $client = $this->container->get('happyr.google.api.client');

        // Determine the level of access your application needs
        $client->getGoogleClient()->setScopes($this->accessScope);

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
            $client->getGoogleClient()->setScopes($this->accessScope);
            $client->authenticate($code);

            $this->storeAccessToken($request, $client->getGoogleClient()->getAccessToken());

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
     * @param Request $request
     * @param array $accessToken
     */
    protected function storeAccessToken(Request $request, array $accessToken)
    {
        $request->getSession()->set(self::SESSION_KEY_GOOGLE_SESSION, $accessToken);
    }
}