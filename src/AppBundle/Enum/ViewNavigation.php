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
            'title' => 'title_demo',
            'isLink' => false,
        ],
        self::ID_PAGE_LOGIN => [
            'href' => '/login',
            'icon' => 'vpn_key',
            'title' => 'title_login',
            'isLink' => false,
        ],
        self::ID_PAGE_FAVORITES => [
            'href' => '/favorites',
            'icon' => 'stars',
            'title' => 'title_favorites',
            'isLink' => false,
        ],
        self::ID_PAGE_SUBSCRIPTIONS => [
            'href' => '/subscriptions',
            'icon' => 'subscriptions',
            'title' => 'title_subscriptions',
            'isLink' => false,
        ],
        self::ID_PAGE_VIDEOS => [
            'href' => '/videos',
            'icon' => 'movie',
            'title' => 'title_videos',
            'isLink' => false,
        ],
        self::ID_LINK_YOUTUBE => [
            'href' => 'https://www.youtube.com',
            'target' => '_blank',
            'icon' => 'play_circle_filled',
            'title' => 'title_youtube',
            'isLink' => true,
        ],
    ];
}
