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

        $pointFrom = 'Русский';
        $graph->addToGraph($pointFrom);

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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Вершина с данным названием отсутсвует в графе');

        $graph->addToGraph('Русский');
        $graph->deleteFromGraph('Остров Попова');
    }

    public function testBindPointsCorrect(): void
    {
        $graph = new Graph();

        $pointFrom = 'Русский';
        $pointTo = 'Остров Попова';

        $graph->addToGraph($pointFrom);
        $graph->addToGraph($pointTo);

        $graph->bindPoints($pointFrom, $pointTo, 10);
        $graph->bindPoints($pointFrom, $pointTo, 12);

        $graph->bindPoints($pointTo, $pointFrom, 4);

        $actual = $graph->print();
        $expected = <<<EOT
        Всего в графе вершин: 2
        '{$pointFrom}': '{$pointTo}' => 12 
        '{$pointTo}': '{$pointFrom}' => 4 
        
        EOT;

        self::assertSame($expected, $actual);
    }

    public function testBindPointsWrongDistance(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Расстояние между вершинами должно быть не менее 1');

        $graph = new Graph();

        $pointFrom = 'Русский';
        $pointTo = 'Остров Попова';

        $graph->addToGraph($pointFrom);
        $graph->addToGraph($pointTo);

        $graph->bindPoints($pointFrom, $pointTo, -1);

    }

    public function testBindPointsSamePoints(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Нельзя указывать одну и ту же вершину для начала пути и конца');

        $pointFrom = 'Русский';

        $graph->addToGraph($pointFrom);

        $graph->bindPoints($pointFrom, $pointFrom, 10);
    }

    public function testBindPointsPointFromNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $notFoundPointFrom = 'Русский';
        $pointTo = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPointFrom}");

        $graph->addToGraph($pointTo);
        $graph->bindPoints($notFoundPointFrom, $pointTo, 10);
    }

    public function testBindPointsPointToNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $pointFrom = 'Русский';
        $notFoundPointTo = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPointTo}");

        $graph->addToGraph($pointFrom);
        $graph->bindPoints($pointFrom, $notFoundPointTo, 10);
    }

    public function testUnbindPointsCorrect(): void
    {
        $graph = new Graph();

        $pointFrom = 'Русский';
        $pointTo = 'Остров Попова';

        $graph->addToGraph($pointFrom);
        $graph->addToGraph($pointTo);

        $graph->bindPoints($pointFrom, $pointTo, 10);
        $graph->unbindPoints($pointFrom, $pointTo);

        $expected = <<<EOL
        Всего в графе вершин: 2
        '{$pointFrom}': 
        '{$pointTo}': 
        
        EOL;

        $actual = $graph->print();
        self::assertSame($expected, $actual);
    }

    public function testUnbindPointsRouteNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $pointFrom = 'Русский';
        $pointTo = 'Остров Попова';
        $this->expectExceptionMessage("Не существует маршрута из {$pointFrom} в {$pointTo}");

        $graph->addToGraph($pointFrom);
        $graph->addToGraph($pointTo);
        $graph->unbindPoints($pointFrom, $pointTo);
    }

    public function testUnbindPointsSamePoints(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $pointFrom = $pointTo = 'Русский';
        $this->expectExceptionMessage('Нельзя указывать одну и ту же вершину для начала пути и конца');

        $graph->addToGraph($pointTo);
        $graph->unbindPoints($pointTo, $pointFrom);
    }

    public function testUnbindPointsPointFromNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $notFoundPointFrom = 'Русский';
        $pointTo = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPointFrom}");

        $graph->addToGraph($pointTo);
        $graph->unbindPoints($notFoundPointFrom, $pointTo);
    }

    public function testUnbindPointsPointToNotFound(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $pointFrom = 'Русский';
        $notFoundPointTo = 'Остров Попова';

        $this->expectExceptionMessage("В графе нет вершины с названием {$notFoundPointTo}");

        $graph->addToGraph($pointFrom);
        $graph->unbindPoints($pointFrom, $notFoundPointTo);
    }
}

