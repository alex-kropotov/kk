<?php

namespace App\Render\Services\Renderers;

use App\Render\Services\Bundles\Bundles;
use App\Render\Services\Bundles\AssetGenerator;
use Tools\Template\TplLoader;

readonly class ViewRenderer
{
    public function __construct(
        private Bundles $bundles,
    ) {}

    public function renderPageWithLayout(string $layoutTpl, string $pageName, array ...$blocks): string
    {
        $this->bundles->setPage($pageName);
        // 2. Генерируем layout
        $layout = TplLoader::get($layoutTpl);

        $assets = [
            'APP_JS' => $this->bundles->getAppJS(),
            'VENDOR_JS' => $this->bundles->getVendorJS(),
            'PAGE_JS' => $this->bundles->getPageJS(),
            'APP_CSS' => $this->bundles->getAppCSS(),
            'VENDOR_CSS' => $this->bundles->getVendorCSS(),
        ];

        $tags = AssetGenerator::generateAssetTags($assets, true);

        $assignments = [
            'SCRIPT_TAGS' => $tags['SCRIPT_TAGS'],
            'STYLE_TAGS' => $tags['STYLE_TAGS'],
        ];

        // Добавляем все переданные блоки
        foreach ($blocks as $block) {
            foreach ($block as $key => $value) {
                $assignments[$key] = $value;
            }
        }

        $layout->assign($assignments);

        return $layout->fetch();
    }
}

