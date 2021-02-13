<?php

declare(strict_types=1);

namespace App\Dijkstra;

use App\Dijkstra\Entities\Graph;

class Dijkstra
{
    private Graph $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }
}
