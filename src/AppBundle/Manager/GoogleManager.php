<?php

namespace AppBundle\Manager;

use HappyR\Google\ApiBundle\Services\GoogleClient;

class GoogleManager
{
    const ACCESS_SCOPE = [
        \Google_Service_YouTube::YOUTUBE,
    ];

    /**
     * @var GoogleClient
     */
    protected $client;

    /**
     * @param $client
     * @param array $accessToken
     */
    public function __construct($client, array $accessToken)
    {
        $this->client = $client;

        $this->client->setAccessToken($accessToken);

        $this->client->getGoogleClient()->setScopes(self::ACCESS_SCOPE);
    }

    public function helloWorld()
    {
        $client = $this->client->getGoogleClient();

        $youtube = new \Google_Service_YouTube($client);

        var_dump($youtube->channels->listChannels('contentDetails')->count());
        die;
    }
}

