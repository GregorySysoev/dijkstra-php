<?php

declare(strict_types=1);

namespace App\Dijkstra;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $pointFrom, string $pointTo)
    {
        parent::__construct("Нет пути из {$pointFrom} в {$pointTo}");
    }
}
