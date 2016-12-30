<?php

namespace AppBundle\Enum;

abstract class ViewNavigation
{
    const ID_PAGE_DEMO = 1;
    const ID_PAGE_LOGIN = 2;
    const ID_PAGE_FAVORITES = 3;
    const ID_PAGE_SUBSCRIPTIONS = 4;
    const ID_PAGE_VIDEOS = 5;
    const ID_LINK_YOUTUBE = 6;

    const PAGES = [
        self::ID_PAGE_DEMO => [
            'href' => '/demo',
            'icon' => 'face',
            'title' => 'Demo',
            'isLink' => false,
        ],
        self::ID_PAGE_LOGIN => [
            'href' => '/',
            'icon' => 'vpn_key',
            'title' => 'Login',
            'isLink' => false,
        ],
        self::ID_PAGE_FAVORITES => [
            'href' => '/favorites',
            'icon' => 'stars',
            'title' => 'Favorites',
            'isLink' => false,
        ],
        self::ID_PAGE_SUBSCRIPTIONS => [
            'href' => '/subscriptions',
            'icon' => 'subscriptions',
            'title' => 'Subscriptions',
            'isLink' => false,
        ],
        self::ID_PAGE_VIDEOS => [
            'href' => '/videos',
            'icon' => 'movie',
            'title' => 'Videos',
            'isLink' => false,
        ],
        self::ID_LINK_YOUTUBE => [
            'href' => 'https://www.youtube.com',
            'target' => '_blank',
            'icon' => 'play_circle_filled',
            'title' => 'YouTube',
            'isLink' => true,
        ],
    ];
}
