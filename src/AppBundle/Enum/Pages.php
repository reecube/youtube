<?php

namespace AppBundle\Enum;

abstract class Pages
{
    const ID_PAGE_DEMO = 1;
    const ID_PAGE_LOGIN = 2;
    const ID_PAGE_FAVORITES = 3;
    const ID_PAGE_SUBSCRIPTIONS = 4;
    const ID_PAGE_VIDEOS = 5;
    const ID_LINK_YOUTUBE = 6;

    const KEY_HREF = 'href';
    const KEY_ICON = 'icon';
    const KEY_TITLE = 'title';
    const KEY_IS_LINK = 'isLink';
    const KEY_ACCESS = 'access';

    const PAGES = [
        self::ID_PAGE_DEMO => [
            self::KEY_HREF => '/demo',
            self::KEY_ICON => 'face',
            self::KEY_TITLE => 'title_demo',
            self::KEY_IS_LINK => false,
            self::KEY_ACCESS => Access::ACCESS_HIDDEN,
        ],
        self::ID_PAGE_LOGIN => [
            self::KEY_HREF => '/login',
            self::KEY_ICON => 'vpn_key',
            self::KEY_TITLE => 'title_login',
            self::KEY_IS_LINK => false,
            self::KEY_ACCESS => Access::ACCESS_GUEST,
        ],
        self::ID_PAGE_FAVORITES => [
            self::KEY_HREF => '/favorites',
            self::KEY_ICON => 'stars',
            self::KEY_TITLE => 'title_favorites',
            self::KEY_IS_LINK => false,
            self::KEY_ACCESS => Access::ACCESS_USER + Access::ACCESS_ADMIN,
        ],
        self::ID_PAGE_SUBSCRIPTIONS => [
            self::KEY_HREF => '/subscriptions',
            self::KEY_ICON => 'subscriptions',
            self::KEY_TITLE => 'title_subscriptions',
            self::KEY_IS_LINK => false,
            self::KEY_ACCESS => Access::ACCESS_USER + Access::ACCESS_ADMIN,
        ],
        self::ID_PAGE_VIDEOS => [
            self::KEY_HREF => '/videos',
            self::KEY_ICON => 'movie',
            self::KEY_TITLE => 'title_videos',
            self::KEY_IS_LINK => false,
            self::KEY_ACCESS => Access::ACCESS_USER + Access::ACCESS_ADMIN,
        ],
        self::ID_LINK_YOUTUBE => [
            self::KEY_HREF => 'https://www.youtube.com',
            'target' => '_blank',
            self::KEY_ICON => 'play_circle_filled',
            self::KEY_TITLE => 'title_youtube',
            self::KEY_IS_LINK => true,
            self::KEY_ACCESS => Access::ACCESS_GUEST + Access::ACCESS_USER + Access::ACCESS_ADMIN,
        ],
    ];
}
