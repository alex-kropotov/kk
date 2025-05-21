<?php

namespace App\Render\Layout\User;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tools\Bundles\AssetGenerator;
use Tools\Bundles\Bundles;
use Tools\Template\TplLoader;
use Tools\Utils\NamedLog;

class LayoutRender {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function renderBase(ContainerInterface $c, string $body): string
    {
        $vTpl = TplLoader::get('Layout/User/vLayoutBase');
        $vTplHeader = TplLoader::get('Layout/User/vHeader');
        $vTplFooter = TplLoader::get('Layout/User/vFooter');
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
            'HEADER' => $vTplHeader->fetch(),
            'FOOTER' => $vTplFooter->fetch(),
            'BODY' => $body,
        ]);



//        $vTpl->assign([
//            'JS_LIBS' => $bundles->getLibsJS(),
//            'JS_APP' => $bundles->getAppJS(),
//            'CSS_BUNDLE' => $bundles->getBundleCSS(),
//            'HEADER' => $vTplHeader->fetch(),
//            'FOOTER' => $vTplFooter->fetch(),
//            'BODY' => $body,
//        ]);
        return $vTpl->fetch();
    }

//    /**
//     * @throws ContainerExceptionInterface
//     * @throws NotFoundExceptionInterface
//     */
//    public static function renderEmpty(ContainerInterface $c, string $body): string
//    {
//        $vTpl = TplLoader::get('Layout/User/vLayoutBase');
//        $bundles = $c->get(Bundles::class);
//        $vTpl->assign([
//            'JS_APP' => $bundles->getAppJS(),
//            'CSS_BUNDLE' => $bundles->getBundleCSS(),
//            'HEADER' => '',
//            'FOOTER' => '',
//            'BODY' => $body,
//        ]);
//        return $vTpl->fetch();
//    }

}
