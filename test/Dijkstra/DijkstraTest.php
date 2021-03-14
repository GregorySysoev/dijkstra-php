<?php

declare(strict_types=1);

namespace App\Tests\Dijkstra;

use App\Dijkstra\Dijkstra;
use App\Dijkstra\Entities\Graph;
use App\Dijkstra\RouteNotFoundException;
use PHPUnit\Framework\TestCase;

class DijkstraTest extends TestCase
{
    public function testGetShortestPath(): void
    {
        $graph = new Graph();

        $point1 = 'Уссурийск';
        $point2 = 'Русский';
        $point3 = 'Остров Попова';

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);
    }

    public function testPointToNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('В графе нет вершины с названием Остров Попова');

        $pointFrom = 'Русский';
        $graph->addToGraph($pointFrom);

        $pointTo = 'Остров Попова';

        $dijkstra = new Dijkstra($graph);
        $dijkstra->getShortestPath($pointFrom, $pointTo);
    }

    public function testPointFromNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('В графе нет вершины с названием Русский');

        $pointFrom = 'Русский';

        $pointTo = 'Остров Попова';
        $graph->addToGraph($pointTo);

        $dijkstra = new Dijkstra($graph);
        $dijkstra->getShortestPath($pointFrom, $pointTo);
    }

    public function testRouteNotFound(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->expectExceptionMessage('Нет пути из Русский в Остров Попова');

        $graph = new Graph();

        $point1 = 'Русский';
        $point2 = 'Остров Попова';

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);

        $graph->bindPoints($point2, $point1, 20);

        $dijkstra = new Dijkstra($graph);
        $dijkstra->getShortestPath($point1, $point2);
    }
}

