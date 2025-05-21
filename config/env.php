<?php

declare(strict_types=1);

use DevCoder\DotEnv;

(new DotEnv(__DIR__.'/../.env'))->load();
$myAppEnv = getenv('MY_APP_ENV');
if ($myAppEnv == 'dev') {
    (new DotEnv(__DIR__.'/../.env.dev'))->load();
}
else if ($myAppEnv == 'prod') {
    (new DotEnv(__DIR__.'/../.env.prod'))->load();
}
else {exit;}