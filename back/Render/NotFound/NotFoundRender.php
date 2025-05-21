<?php

namespace App\Render\NotFound;

use Tools\Template\TplLoader;

class NotFoundRender
{
    public static function render(): string
    {
//        $vTpl = TplLoader::get('Controllers/NotFound/vNotFound');
        $vTpl = TplLoader::get('vNotFound');
        return $vTpl->fetch();
    }
}
