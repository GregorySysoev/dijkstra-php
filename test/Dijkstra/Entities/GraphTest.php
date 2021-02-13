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

        $pointName = 'Русский';
        $graph->addToGraph($pointName);

        $expectedValue = 'Русский: ';
        $actualValue = $graph->printGraph();

        self::assertSame($expectedValue, $actualValue);
    }

    public function testAddUncorrect(): void
    {
        $graph = new Graph();
        $this->expectException(\InvalidArgumentException::class);

        $pointName = 'Русский';
        $pointName2 = 'Русский';
        $graph->addToGraph($pointName);
        $graph->addToGraph($pointName2);
    }

    private function testAddProvider(): iterable
    {
        yield [];
    }
}

