<?php

namespace Tools\Bundles;

use Tools\Utils\NamedLog;
use App\Domain\Service\URI\UriManager;

class Bundles
{
    private string $appCSS = '';
    private string $appJS = '';

    private string $vendorCSS = '';
    private string $vendorJS = '';

    private string $pageJS = '';

    function __construct(UriManager $uriManager)
    {
        $c = file_get_contents('./.vite/manifest.json');
        $c = json_decode($c, true);
        $appRoot = $c['front/app.js'] ?? null;
        if ($appRoot) {
            $this->appJS = $appRoot['file'] ?? '';
            $this->appCSS = $appRoot['css'][0] ?? '';
            $vendorRoot = $appRoot['imports'][0];
            $this->vendorJS = $c[$vendorRoot]['file'] ?? '';
            $this->vendorCSS = $c[$vendorRoot]['css'][0] ?? '';
            $curPageName = $uriManager->getCurPage();
            if ($curPageName !== '') {
                $pageRoot = $c['front/js/pages/'.$curPageName.'.js'] ?? null;
                if ($pageRoot) {
                    $this->pageJS = $pageRoot['file'] ?? '';
                }
            }
            NamedLog::write('bundles', '$this->appJS', $this->appJS);
            NamedLog::write('bundles', '$this->appCSS', $this->appCSS);
            NamedLog::write('bundles', '$this->vendorJS', $this->vendorJS);
            NamedLog::write('bundles', '$this->vendorCSS', $this->vendorCSS);
            NamedLog::write('bundles', '$this->pageJS', $this->pageJS);
            NamedLog::write('bundles', '************************');
        }
    }

    public function getAppCSS(): string
    {
        return $this->appCSS;
    }

    public function getAppJS(): string
    {
        return $this->appJS;
    }

    public function getVendorCSS(): string
    {
        return $this->vendorCSS;
    }

    public function getVendorJS(): string
    {
        return $this->vendorJS;
    }

    public function getPageJS(): string
    {
        return $this->pageJS;
    }

}
