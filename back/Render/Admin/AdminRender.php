<?php

namespace App\Render\Admin;

use App\Components\CardList\CardListRender;
use App\Components\Filter\FilterRender;
use App\Components\Location\LocationRender;
use Tools\Template\TplLoader;

class AdminRender
{
    public static function render(array $params): string
    {
        $vTpl = TplLoader::get('vAdmin');

        $vTpl->assign([
//            'LOCATION' => LocationRender::render(),
//            'FILTER' => $var == 2 ? FilterRender::render2() : FilterRender::render(),
//            'FILTER' => FilterRender::render3(),
//            'CARDS' => CardListRender::renderCardList(),
        ]);
        return $vTpl->fetch();
    }

    public static function adminLogin(string $redirectFrom): string
    {
        $vTpl = TplLoader::get('vAdminLogin');
        $vTpl->assign([
            'REDIRECT_FROM' => $redirectFrom
        ]);
        return $vTpl->fetch();
    }

}
