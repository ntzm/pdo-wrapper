<?php declare(strict_types=1);

namespace PdoWrapper\Emitting;

interface Listener
{
    public function startExecute(string $query, array $bindings = []): QueryId;

    public function endExecute(QueryId $id): void;
}
