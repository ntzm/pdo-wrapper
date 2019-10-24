<?php declare(strict_types=1);

namespace PdoWrapper;

interface PdoWrapper
{
    public function prepare(string $statement): StatementWrapper;

    public function exec(string $statement): int;

    public function query(string $statement): StatementWrapper;
}
