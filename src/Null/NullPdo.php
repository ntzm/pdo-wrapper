<?php declare(strict_types=1);

namespace PdoWrapper\Null;

use PdoWrapper\PdoWrapper;
use PdoWrapper\StatementWrapper;

final class NullPdo implements PdoWrapper
{
    public function prepare(string $statement): StatementWrapper
    {
        return new NullStatement();
    }

    public function exec(string $statement): int
    {
        return 0;
    }

    public function query(string $statement): StatementWrapper
    {
        return new NullStatement();
    }
}
