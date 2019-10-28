<?php declare(strict_types=1);

namespace PdoWrapper;

use PDO;

interface StatementWrapper
{
    public function execute(array $bindings = []): bool;

    /**
     * @param int|string $parameter
     * @param mixed $value
     */
    public function bindValue($parameter, $value, int $type = PDO::PARAM_STR): bool;

    /** @return array|false */
    public function fetchAll(int $style = null);

    /** @return mixed */
    public function fetch(int $style = null);

    /** @return mixed */
    public function fetchColumn(int $columnNumber = 0);

    public function rowCount(): int;
}
