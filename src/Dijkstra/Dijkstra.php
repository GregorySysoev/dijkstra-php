<?php

declare(strict_types=1);

namespace App\Dijkstra;

use App\Dijkstra\Entities\Graph;
use SplPriorityQueue;
use SplStack;

class Dijkstra
{
    private Graph $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    public function getShortestPath(string $pointFrom, string $pointTo): string
    {
        $this->graph->validatePoint1Point2($pointFrom, $pointTo);

        // массив кратчайших путей к каждому узлу
        $shortestPointRoutes = [];
        // массив "предшественников" для каждого узла
        $previousRoutes = [];
        // очередь всех неоптимизированных узлов
        $queries = new SplPriorityQueue();

        foreach ($this->graph->getPoints() as $point => $routes) {
            // устанавливаем изначальные расстояния как бесконечность
            $shortestPointRoutes[$point] = INF;
            // никаких узлов позади нет
            $previousRoutes[$point] = null;
            foreach ($routes as $route => $distance) {
                // воспользуемся ценой связи как приоритетом
                $queries->insert($route, $distance);
            }
        }

        $shortestPointRoutes[$pointFrom] = 0;

        while (!$queries->isEmpty()) {
            $current = $queries->extract();
            foreach ($this->graph->getPoints()[$current] as $point => $distance) {
                $alt = $shortestPointRoutes[$current] + $distance;
                if ($alt < $shortestPointRoutes[$point]) {
                    $shortestPointRoutes[$point] = $alt;
                    $previousRoutes[$point] = $current; // добавим соседа как предшествующий этому узла
                }
            }
        }

        // теперь мы можем найти минимальный путь
        // используя обратный проход
        $stack = new SplStack(); // кратчайший путь как стек

        $current = $pointTo;
        $finalDistance = 0;

        while (isset($previousRoutes[$current])) {
            $stack->push($current);
            $finalDistance += $this->graph->getPoints()[$current][$previousRoutes[$current]];
            $current = $previousRoutes[$current];
        }

        if ($stack->isEmpty()) {
            throw new RouteNotFoundException($pointFrom, $pointTo);
        }
        // добавим стартовый узел и покажем весь путь
        // в обратном (LIFO) порядке
        $stack->push($pointFrom);

        return "{$finalDistance}: {$this->printRoute($stack)}";
    }

    private function printRoute(SplStack $stack): string
    {
        $path = '';
        $sep = '->';

        $path .= $stack->pop();

        foreach ($stack as $point) {
            $path .= $sep;
            $path .= $point;
        }

        return $path;
    }
}
