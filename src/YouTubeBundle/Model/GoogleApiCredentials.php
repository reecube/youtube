<?php

namespace YouTubeBundle\Model;

class GoogleApiCredentials
{
    /**
     * @var string
     */
    protected $google_api_application_name;

    /**
     * @var string
     */
    protected $google_api_developer_key;

    /**
     * @var array
     */
    protected $google_api_oauth2_json_array;

    /**
     * @return string
     */
    public function getGoogleApiApplicationName()
    {
        return $this->google_api_application_name;
    }

    /**
     * @param string $google_api_application_name
     */
    public function setGoogleApiApplicationName($google_api_application_name)
    {
        $this->google_api_application_name = $google_api_application_name;
    }

    /**
     * @return string
     */
    public function getGoogleApiDeveloperKey()
    {
        return $this->google_api_developer_key;
    }

    /**
     * @param string $google_api_developer_key
     */
    public function setGoogleApiDeveloperKey($google_api_developer_key)
    {
        $this->google_api_developer_key = $google_api_developer_key;
    }

    /**
     * @return string
     */
    public function getGoogleApiOauth2JsonArray()
    {
        return $this->google_api_oauth2_json_array;
    }

    /**
     * @param array $google_api_oauth2_json_array
     */
    public function setGoogleApiOauth2JsonArray(array $google_api_oauth2_json_array)
    {
        $this->google_api_oauth2_json_array = $google_api_oauth2_json_array;
    }
}
