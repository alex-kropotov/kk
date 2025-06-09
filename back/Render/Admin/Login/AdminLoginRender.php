<?php

declare(strict_types=1);

namespace App\Render\Admin\Login;

use Tools\Template\TplLoader;

class AdminLoginRender
{
    public static function render(string $redirectFrom): string
    {
        $vTpl = TplLoader::get('vAdminLogin');
        $vTpl->assign([
            'REDIRECT_FROM' => $redirectFrom
        ]);
        return $vTpl->fetch();
    }
}
