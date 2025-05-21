<?php

namespace App\Render\Layout\Admin;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tools\Bundles\AssetGenerator;
use Tools\Bundles\Bundles;
use Tools\Template\TplLoader;

class AdminLayoutRender {
    public static function render(string $header, string $body, string $footer): string
    {
        $vTpl = TplLoader::get('vAdminLayout');
        $bundles = $c->get(Bundles::class);

        $assets = [
            'APP_JS' => $bundles->getAppJS(),
            'VENDOR_JS' => $bundles->getVendorJS(),
            'PAGE_JS' => $bundles->getPageJS(),
            'APP_CSS' => $bundles->getAppCSS(),
            'VENDOR_CSS' => $bundles->getVendorCSS(),
        ];

        // Генерируем теги ресурсов
        $assetTags = AssetGenerator::generateAssetTags($assets, true);

        $vTpl->assign([
            'SCRIPT_TAGS' => $assetTags['SCRIPT_TAGS'],
            'STYLE_TAGS' => $assetTags['STYLE_TAGS'],
            'HEADER' => $header,
            'FOOTER' => $footer,
            'BODY' => $body,
        ]);

        return $vTpl->fetch();
    }

}
