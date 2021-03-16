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

    public function bindPoints(string $pointFrom, string $pointTo, int $distance): void
    {
        if ($distance < 1) {
            throw new \InvalidArgumentException('Расстояние между вершинами должно быть не менее 1');
        }

        $this->validatePointToPointFrom($pointFrom, $pointTo);

        $this->points[$pointFrom][$pointTo] = $distance;
    }

    public function unbindPoints(string $pointFrom, string $pointTo): void
    {
        $this->validatePointToPointFrom($pointFrom, $pointTo);

        if (!isset($this->points[$pointFrom][$pointTo])) {
            throw new \InvalidArgumentException("Не существует маршрута из {$pointFrom} в {$pointTo}");
        }
        unset($this->points[$pointFrom][$pointTo]);
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    public function validatePointToPointFrom(string $pointFrom, string $pointTo): void
    {
        if ($pointFrom === $pointTo) {
            throw new \InvalidArgumentException('Нельзя указывать одну и ту же вершину');
        }

        if (!$this->graphContainsPoint($pointFrom)) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$pointFrom}");
        }

        if (!$this->graphContainsPoint($pointTo)) {
            throw new \InvalidArgumentException("В графе нет вершины с названием {$pointTo}");
        }
    }

    private function graphContainsPoint(string $point): bool
    {
        return isset($this->points[$point]);
    }
}
