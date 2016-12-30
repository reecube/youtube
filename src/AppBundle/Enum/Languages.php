<?php

namespace AppBundle\Enum;

abstract class Languages
{
    const ID_LANG_EN = 1;
    const ID_LANG_DE = 2;
    const ID_LANG_FR = 3;

    const KEY_LOCALE = 'locale';
    const KEY_TITLE = 'title';
    const KEY_URL = 'url';

    const LANGUAGES = [
        self::ID_LANG_EN => [
            self::KEY_LOCALE => 'en',
            self::KEY_TITLE => 'English',
            self::KEY_URL => null,
        ],
        self::ID_LANG_DE => [
            self::KEY_LOCALE => 'de',
            self::KEY_TITLE => 'Deutsch',
            self::KEY_URL => null,
        ],
        self::ID_LANG_FR => [
            self::KEY_LOCALE => 'fr',
            self::KEY_TITLE => 'Français',
            self::KEY_URL => null,
        ],
    ];
}
