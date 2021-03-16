<?php

declare(strict_types=1);

namespace App\Tests\Dijkstra\Entities;

use App\Dijkstra\Entities\Graph;
use PHPUnit\Framework\TestCase;

/**
 * @covers Graph
 */
class GraphTest extends TestCase
{
    public function testAddCorrect(): void
    {
        $graph = new Graph();

        $point1 = 'Русский';
        $graph->addToGraph($point1);

        $expectedValue = "Всего в графе вершин: 1\n'Русский': \n";
        $actualValue = $graph->print();

        self::assertSame($expectedValue, $actualValue);
    }

    public function testAddUncorrect(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Вершина с данным названием уже присутствует в графе');

        $graph->addToGraph('Русский');
        $graph->addToGraph('Русский');
    }

    public function testDeleteCorrect(): void
    {
        $graph = new Graph();
        $graph->addToGraph('Русский');
        $graph->deleteFromGraph('Русский');
        $actual = $graph->print();
        self::assertSame("Всего в графе вершин: 0\n", $actual);
    }

    public function testDeleteUncorrect(): void
    {
        $graph = new Graph();
        $pointNotFound = 'Остров Попова';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Вершина {$pointNotFound} отсутсвует в графе");

        $graph->addToGraph('Русский');
        $graph->deleteFromGraph($pointNotFound);
    }

    public function testBindPointsCorrect(): void
    {
        $graph = new Graph();

        $point1 = 'Русский';
        $point2 = 'Остров Попова';

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);

        $graph->bindPoints($point2, $point1, 4);
        $graph->bindPoints($point1, $point2, 5);

        $actual = $graph->print();
        $expected = <<<EOT
        Всего в графе вершин: 2
        '{$point1}': '{$point2}' => 5 
        '{$point2}': '{$point1}' => 5 
        
        EOT;

        self::assertSame($expected, $actual);
    }

    public function testBindPointsWrongDistance(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Расстояние между вершинами должно быть не менее 1');

        $graph = new Graph();

        $point1 = 'Русский';
        $point2 = 'Остров Попова';

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);

        $graph->bindPoints($point1, $point2, -1);
    }

    public function testBindPointsSamePoints(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Нельзя указывать одну и ту же вершину');

        $point1 = 'Русский';

        $graph->addToGraph($point1);

        $graph->bindPoints($point1, $point1, 10);
    }

    public function testBindPointsPoint1NotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $notFoundPoint1 = 'Русский';
        $point2 = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPoint1}");

        $graph->addToGraph($point2);
        $graph->bindPoints($notFoundPoint1, $point2, 10);
    }

    public function testBindPointsPoint2NotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $point1 = 'Русский';
        $notFoundPoint2 = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPoint2}");

        $graph->addToGraph($point1);
        $graph->bindPoints($point1, $notFoundPoint2, 10);
    }

    public function testUnbindPointsCorrect(): void
    {
        $graph = new Graph();

        $point1 = 'Русский';
        $point2 = 'Остров Попова';

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);

        $graph->bindPoints($point1, $point2, 10);
        $graph->unbindPoints($point1, $point2);

        $expected = <<<EOL
        Всего в графе вершин: 2
        '{$point1}': 
        '{$point2}': 
        
        EOL;

        $actual = $graph->print();
        self::assertSame($expected, $actual);
    }

    public function testUnbindPointsRouteNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $point1 = 'Русский';
        $point2 = 'Остров Попова';
        $this->expectExceptionMessage("Не существует маршрута из {$point1} в {$point2}");

        $graph->addToGraph($point1);
        $graph->addToGraph($point2);
        $graph->unbindPoints($point1, $point2);
    }

    public function testUnbindPointsSamePoints(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $point1 = $point2 = 'Русский';
        $this->expectExceptionMessage('Нельзя указывать одну и ту же вершину');

        $graph->addToGraph($point2);
        $graph->unbindPoints($point2, $point1);
    }

    public function testUnbindPointsPoint1NotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $notFoundPoint1 = 'Русский';
        $point2 = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPoint1}");

        $graph->addToGraph($point2);
        $graph->unbindPoints($notFoundPoint1, $point2);
    }

    public function testUnbindPointsPoint2NotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $point1 = 'Русский';
        $notFoundPoint2 = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPoint2}");

        $graph->addToGraph($point1);
        $graph->unbindPoints($point1, $notFoundPoint2);
    }
}

