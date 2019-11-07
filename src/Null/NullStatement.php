<?php declare(strict_types=1);

namespace PdoWrapper\Null;

use PDO;
use PdoWrapper\StatementWrapper;

final class NullStatement implements StatementWrapper
{
    public function execute(array $bindings = []): bool
    {
        return true;
    }

    public function bindValue($parameter, $value, int $type = PDO::PARAM_STR): bool
    {
        return true;
    }

    public function fetchAll(int $style = null)
    {
        return [];
    }

    public function fetch(int $style = null)
    {
        return [];
    }

    public function fetchColumn(int $columnNumber = 0)
    {
        return '';
    }

    public function rowCount(): int
    {
        return 0;
    }
}
