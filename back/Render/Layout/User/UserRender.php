<?php

namespace App\Render\Layout\User;

use Tools\Template\TplLoader;

class UserRender
{
    public static function header(): string
    {
        $vHeader = TplLoader::get('vUserHeader');
        $vPreloader = TplLoader::get('vPreloader');
        $vSidebar = TplLoader::get('vSidebar');
        $vLogoMenuBurger = TplLoader::get('vLogoMenuBurger');
        $vHeader->assign(
            [
                'PRELOADER' => $vPreloader->fetch(),
                'SIDEBAR' => $vSidebar->fetch(),
                'LOGO_MENU_BURGER' => $vLogoMenuBurger->fetch()
            ]
        );

        return $vHeader->fetch();
    }

    public static function footer(): string
    {
        $vUserFooter = TplLoader::get('vUserFooter');
        return $vUserFooter->fetch();
    }
}
