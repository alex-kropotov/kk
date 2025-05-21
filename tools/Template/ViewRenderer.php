<?php

namespace Tools\Template;

use Tools\Bundles\Bundles;
use Tools\Bundles\AssetGenerator;

readonly class ViewRenderer
{
    public function __construct(
        private Bundles $bundles,
    ) {}

    public function renderPageWithLayout(string $layoutTpl, array ...$blocks): string
    {

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

