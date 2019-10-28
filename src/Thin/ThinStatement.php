<?php declare(strict_types=1);

namespace PdoWrapper\Emitting;

use PDO;
use PDOStatement;
use PdoWrapper\StatementWrapper;

final class ThinStatement implements StatementWrapper
{
    /** @var PDOStatement */
    private $statement;

    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function execute(array $bindings = []): bool
    {
        if ($bindings) {
            return $this->statement->execute($bindings);
        }

        return $this->statement->execute();
    }

    public function bindValue($parameter, $value, int $type = PDO::PARAM_STR): bool
    {
        return $this->statement->bindValue($parameter, $value, $type);
    }

    public function fetchAll(int $style = null)
    {
        if ($style === null) {
            return $this->statement->fetchAll();
        }

        return $this->statement->fetchAll($style);
    }

    public function fetch(int $style = null)
    {
        if ($style === null) {
            return $this->statement->fetch();
        }

        return $this->statement->fetch($style);
    }

    public function fetchColumn(int $columnNumber = 0)
    {
        return $this->statement->fetchColumn($columnNumber);
    }
}
