#!/usr/bin/env php
<?php

declare(strict_types=1);

const BASE_DIR = 'back/Feature';
const NAMESPACE_ROOT = 'App\\Feature';

function studly(string $value): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_', '/'], ' ', $value)));
}

function getTypeSuffix(string $type): string {
    return match ($type) {
        'api' => 'Api',
        'view' => 'View',
        default => throw new InvalidArgumentException("Unknown type '$type'. Use 'api' or 'view'."),
    };
}

function generate(string $inputPath, string $type): void {
    $parts = explode('/', trim($inputPath, '/'));
    if (count($parts) < 2) {
        throw new InvalidArgumentException("Input must be at least Interface/Feature (e.g. Admin/Login)");
    }

//    $interface = array_shift($parts);
//    $feature = studly(implode('', $parts));
//    $classPrefix = $interface . $feature;
//
//
//    $fullPath = BASE_DIR . '/' . $interface . '/' . implode('/', $parts) . '/' . ucfirst($type);
//
//    if (is_dir($fullPath)) {
//        echo "❌ Feature '$inputPath' уже существует по пути '$fullPath'. Ничего не создано.\n";
//        return;
//    }
//
//
//    $namespace = NAMESPACE_ROOT . '\\' . $interface . '\\' . implode('\\', $parts) . '\\' . ucfirst($type);

    $interface = array_shift($parts); // Admin
    $featureName = array_pop($parts); // LoginForm или LoginCheck
    $groupPath = implode('/', $parts); // Auth
    $groupNamespace = implode('\\', $parts); // Auth
    $typeDir = ucfirst($type); // View или Api

    $feature = studly($featureName);
    $classPrefix = $interface . studly($groupNamespace . $feature);

    // Папка: back/Feature/Admin/Auth/View/LoginForm
    $fullPath = BASE_DIR . '/' . $interface
        . ($groupPath ? '/' . $groupPath : '')
        . '/' . $typeDir
        . '/' . $feature;

    // Неймспейс: App\Feature\Admin\Auth\View\LoginForm
    $namespace = NAMESPACE_ROOT . '\\' . $interface
        . ($groupNamespace ? '\\' . $groupNamespace : '')
        . '\\' . $typeDir
        . '\\' . $feature;


    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0777, true);
    }



    // Command
    $commandClass = $classPrefix . getTypeSuffix($type) . 'Command';
    $commandFile = "$fullPath/{$commandClass}.php";
    file_put_contents($commandFile, generateCommand($namespace, $commandClass));

    // Controller
    $controllerClass = $classPrefix . getTypeSuffix($type) . 'Controller';
    $controllerFile = "$fullPath/{$controllerClass}.php";
    file_put_contents($controllerFile, generateController($namespace, $controllerClass, $commandClass, $type));

    // Handler
    $handlerClass = $classPrefix . getTypeSuffix($type) . 'Handler';
    $handlerFile = "$fullPath/{$handlerClass}.php";
    file_put_contents($handlerFile, generateHandler($namespace, $handlerClass, $commandClass));

    echo "Generated:\n- $controllerClass\n- $commandClass\n- $handlerClass\n";
}

function generateController(string $namespace, string $className, string $commandClass, string $type): string {
    if ($type === 'api') {
        return <<<PHP
<?php

namespace $namespace;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Tools\CommandBus\BaseController;

final class $className extends BaseController
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface
    {
        \$params = \$request->getParsedBody();

        \$command = \$this->dtoFactory->create($commandClass::class, \$params);
        \$result = \$this->commandBus->dispatch(\$command);

        \$response->getBody()->write(json_encode(\$result->getAsArray()));
        return \$response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
PHP;
    }

    return <<<PHP
<?php

namespace $namespace;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tools\CommandBus\BaseController;

class $className extends BaseController {
    public function __invoke(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface
    {
        \$params = \$request->getParsedBody();
        
        \$command = \$this->dtoFactory->create($commandClass::class, \$params);
        \$result = \$this->commandBus->dispatch(\$command);
        \$response->getBody()->write(\$result->getHtml());
        return \$response;
    }
}
PHP;
}

function generateCommand(string $namespace, string $className): string {
    return <<<PHP
<?php

namespace $namespace;

use Tools\CommandBus\CommandInterface;

class $className implements CommandInterface
{
    public function __construct() {}
}
PHP;
}

function generateHandler(string $namespace, string $className, string $commandClass): string {
    return <<<PHP
<?php

namespace $namespace;

use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;

#[AsCommandHandler($commandClass::class)]
readonly class $className implements CommandHandlerInterface
{
    public function __construct() {}

    public function handle(CommandInterface \$command): CommandHandlerResultInterface
    {
        // TODO: implement handler logic
    }
}
PHP;
}
// php tools/generate-feature.php Admin/Auth/Login api
// Run script
$argv = $_SERVER['argv'];
if (count($argv) !== 3) {
    echo "Usage: php generate-feature.php Interface/Path/To/Feature api|view\n";
    exit(1);
}

generate($argv[1], strtolower($argv[2]));

