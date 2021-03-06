<?php

namespace YouTubeBundle\Handler;

use Google_Client;
use Google_Service_YouTube;
use YouTubeBundle\Model\GoogleApiCredentials;

class YouTubeHandler
{
    /**
     * @var GoogleApiCredentials
     */
    protected $credentials;

    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * @param GoogleApiCredentials $credentials
     */
    public function __construct(GoogleApiCredentials $credentials)
    {
        $this->credentials = $credentials;

        $this->client = new Google_Client();

        $this->client->setApplicationName($this->credentials->getGoogleApiApplicationName());
        $this->client->setDeveloperKey($this->credentials->getGoogleApiDeveloperKey());

        $this->client->setAuthConfig($this->credentials->getGoogleApiOauth2JsonArray());

        // This should return a refresh token
        $this->client->setApprovalPrompt('force');
        $this->client->setAccessType('offline');

        $this->client->addScope(Google_Service_YouTube::YOUTUBE);
    }

    /**
     * @return GoogleApiCredentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @return string
     */
    public function createAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * @param string $code
     * @return array access token
     */
    public function handleRedirectionCode($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        $this->client->setAccessToken($accessToken);

        return $accessToken;
    }

    /**
     * @param array $accessToken
     * @return bool
     */
    public static function isAccessTokenValid(array $accessToken)
    {
        $tsCreated = $accessToken['created'];
        $tsNow = time();

        $timePassed = $tsNow - $tsCreated;

        $expiresIn = $accessToken['expires_in'];

        return $timePassed < $expiresIn;
    }

    /**
     * @param array $accessToken
     * @param bool $forceRefresh
     * @return array|null the new access token or null if there is no access token
     */
    public function authorize(array $accessToken, $forceRefresh = false)
    {
        $this->client->setAccessToken($accessToken);


        if (!$forceRefresh) {
            $valid = self::isAccessTokenValid($accessToken);

            if ($valid) {
                return $accessToken;
            }
        }

        if (!isset($accessToken['refresh_token'])) {
            return null;
        }

        $newAccessToken = $this->client->fetchAccessTokenWithRefreshToken();

        $newAccessToken['refresh_token'] = $accessToken['refresh_token'];

        return $newAccessToken;
    }
}
