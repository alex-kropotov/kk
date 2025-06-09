<?php

namespace App\Render\Services\Renderers;


use Tools\Template\TplLoader;

readonly class ViewRendererNoBundle
{
    public function renderPageWithLayout(string $layoutTpl, array ...$blocks): string
    {

        // 2. Генерируем layout
        $layout = TplLoader::get($layoutTpl);

        $assignments = [];

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

