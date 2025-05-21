<?php

declare(strict_types=1);

namespace App\Domain\Service\URI;

use Psr\Http\Message\UriInterface;
use Tools\Utils\NamedLog;

class UriManager
{
    /**
     * Property to store the request URI
     */
    private ?UriInterface $uri;

    /**
     * Constructor that accepts UriInterface
     */
    public function __construct(UriInterface $uri = null)
    {
        $this->uri = $uri;
    }

    /**
     * Get the current page (last component of the path)
     * This is used by client code to get the current page
     */
    public function getCurPage(): string
    {
        if ($this->uri === null) {
            return 'home';
        }

        $path = $this->uri->getPath();
        NamedLog::write('URI', '$path '  . $path);
        // Remove trailing slash if present
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        // Get the last component of the path
        $pathParts = explode('/', $path);
        $lastComponent = end($pathParts);

        // If path is empty or only root (/)
        if (empty($lastComponent)) {
            return 'home';
        }

        NamedLog::write('URI', '$lastComponent ' . $lastComponent);
        return $lastComponent;
    }
}
