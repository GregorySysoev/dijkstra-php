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

        $graph->addToGraph('A');
        $graph->addToGraph('B');
        $graph->addToGraph('C');
        $graph->addToGraph('D');
        $graph->addToGraph('E');
        $graph->addToGraph('F');

        $graph->bindPoints('A', 'B', 3);
        $graph->bindPoints('B', 'A', 3);
        $graph->bindPoints('D', 'A', 3);
        $graph->bindPoints('A', 'D', 3);
        $graph->bindPoints('F', 'A', 6);
        $graph->bindPoints('A', 'F', 6);
        $graph->bindPoints('B', 'D', 1);
        $graph->bindPoints('D', 'B', 1);
        $graph->bindPoints('B', 'E', 3);
        $graph->bindPoints('E', 'B', 3);
        $graph->bindPoints('C', 'E', 2);
        $graph->bindPoints('E', 'C', 2);
        $graph->bindPoints('C', 'F', 3);
        $graph->bindPoints('F', 'C', 3);
        $graph->bindPoints('D', 'E', 1);
        $graph->bindPoints('E', 'D', 1);
        $graph->bindPoints('D', 'F', 2);
        $graph->bindPoints('F', 'D', 2);
        $graph->bindPoints('E', 'F', 5);
        $graph->bindPoints('F', 'E', 5);


        $dijkstra = new Dijkstra($graph);
        $res = $dijkstra->getShortestPath('D', 'C');
        self::assertSame('3: D->E->C', $res);
        $res = $dijkstra->getShortestPath('C', 'A');
        self::assertSame('6: C->E->D->A', $res);
        $res = $dijkstra->getShortestPath('B', 'F');
        self::assertSame('3: B->D->F', $res);
        $res = $dijkstra->getShortestPath('F', 'A');
        self::assertSame('5: F->D->A', $res);
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

        $dijkstra = new Dijkstra($graph);
        $dijkstra->getShortestPath($point1, $point2);
    }
}

