<?php

namespace App\Render\Services\Bundles;

use Tools\Utils\NamedLog;
use App\Domain\Service\URI\UriManager;

class Bundles
{
    private string $appCSS = '';
    private string $appJS = '';

    private string $vendorCSS = '';
    private string $vendorJS = '';

    private string $pageJS = '';

    function __construct()
    {

    }

    public function setPage(string $pageName = ''): void
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
            if ($pageName !== '') {
                $pageRoot = $c['front/js/pages/'.$pageName.'.js'] ?? null;
                if ($pageRoot) {
                    $this->pageJS = $pageRoot['file'] ?? '';
                }
            }
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
