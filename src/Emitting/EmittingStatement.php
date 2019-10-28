<?php declare(strict_types=1);

namespace PdoWrapper\Emitting;

use PDO;
use PDOStatement;
use PdoWrapper\StatementWrapper;

final class EmittingStatement implements StatementWrapper
{
    /** @var PDOStatement */
    private $statement;

    /** @var Listener */
    private $listener;

    /** @var array */
    private $bindings = [];

    public function __construct(PDOStatement $statement, Listener $listener)
    {
        $this->statement = $statement;
        $this->listener = $listener;
    }

    public function execute(array $bindings = []): bool
    {
        $id = $this->listener->startExecute($this->statement->queryString, $bindings ?: $this->bindings);

        if ($bindings) {
            $result = $this->statement->execute($bindings);
        } else {
            $result = $this->statement->execute();
        }

        $this->listener->endExecute($id);

        return $result;
    }

    public function bindValue($parameter, $value, int $type = PDO::PARAM_STR): bool
    {
        $this->bindings[$parameter] = $value;

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
