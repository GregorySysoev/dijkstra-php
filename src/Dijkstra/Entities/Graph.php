<?php

declare(strict_types=1);

namespace App\Dijkstra\Entities;

use App\Tests\Dijkstra\Entities\GraphTest;

/**
 * @see GraphTest
 */
class Graph
{
    private array $points;

    public function __construct()
    {
        $this->points = [];
    }

    public function addToGraph(string $pointName): void
    {
        if (isset($this->points[$pointName])) {
            throw new \InvalidArgumentException('Вершина с данным названием уже присутствует в графе');
        }
        $this->points[$pointName] = [];
    }

    public function deleteFromGraph(string $pointName): void
    {
        if (!isset($this->points[$pointName])) {
            throw new \InvalidArgumentException('Вершина с данным названием отсутсвует в графе');
        }

        foreach ($this->points as $key => $roads) {
            if (isset($roads[$pointName])) {
                unset($this->points[$key][$pointName]);
            }
        }
        unset($this->points[$pointName]);
    }

    public function print(): string
    {
        $countOfPoints = count($this->points);
        $result = "Всего в графе вершин: {$countOfPoints}" . PHP_EOL;
        foreach ($this->points as $key => $roads) {
            $result .= "'{$key}': ";
            foreach ($roads as $way => $distance) {
                $result .= "'{$way}' => {$distance} ";
            }
            $result .= PHP_EOL;
        }
        return $result;
    }

    public function bindPoints(string $point1, string $point2, int $distance): void
    {
        if ($distance < 1) {
            throw new \InvalidArgumentException('Расстояние между вершинами должно быть не менее 1');
        }

        $this->validatePoint1Point2($point1, $point2);

        $this->points[$point1][$point2] = $distance;
        $this->points[$point2][$point1] = $distance;
    }

    public function unbindPoints(string $point1, string $point2): void
    {
        $this->validatePoint1Point2($point1, $point2);

        if (!isset($this->points[$point1][$point2])) {
            throw new \InvalidArgumentException("Не существует маршрута из {$point1} в {$point2}");
        }
        unset($this->points[$point1][$point2], $this->points[$point2][$point1]);
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    public function validatePoint1Point2(string $point1, string $point2): void
    {
        if ($point1 === $point2) {
            throw new \InvalidArgumentException('Нельзя указывать одну и ту же вершину');
        }

        if (!$this->graphContainsPoint($point1)) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$point1}");
        }

        if (!$this->graphContainsPoint($point2)) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$point2}");
        }
    }

    private function graphContainsPoint(string $point): bool
    {
        return isset($this->points[$point]);
    }
}
