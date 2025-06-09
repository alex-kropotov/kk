<?php

declare(strict_types=1);

namespace App\Render\Admin\Layout;

use Tools\Template\TplLoader;

class AdminNavbarRender
{
    public static function get(string $pageName): string
    {
        $vTpl = TplLoader::get('vAdminNavbar');
        $vTpl->assign([
            $pageName => 'active'
        ]);
        return $vTpl->fetch();
    }
}
