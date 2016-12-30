<?php

namespace AppBundle\Enum;

abstract class Access
{
    const ACCESS_HIDDEN = 1;
    const ACCESS_GUEST = 2;
    const ACCESS_USER = 4;
    const ACCESS_ADMIN = 8;

    /**
     * @param int $validAccess
     * @param int $userAccess
     * @return bool
     */
    public static function hasAccess($validAccess, $userAccess)
    {
        return ($validAccess & $userAccess) !== 0;
    }
}
