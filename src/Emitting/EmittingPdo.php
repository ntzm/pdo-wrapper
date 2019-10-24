<?php declare(strict_types=1);

namespace PdoWrapper\Emitting;

use InvalidArgumentException;
use PDO;
use PdoWrapper\PdoWrapper;
use PdoWrapper\StatementWrapper;

final class EmittingPdo implements PdoWrapper
{
    /** @var Listener */
    private $listener;

    /** @var PDO */
    private $pdo;

    public function __construct(Listener $listener, PDO $pdo)
    {
        if ($pdo->getAttribute(PDO::ATTR_ERRMODE) !== PDO::ERRMODE_EXCEPTION) {
            throw new InvalidArgumentException('PDO must have ATTR_ERRMODE set to ERRMODE_EXCEPTION');
        }

        $this->listener = $listener;
        $this->pdo = $pdo;
    }

    public function prepare(string $statement): StatementWrapper
    {
        return new EmittingStatement($this->pdo->prepare($statement), $this->listener);
    }

    public function exec(string $statement): int
    {
        $id = $this->listener->startExecute($statement);

        $result = $this->pdo->exec($statement);

        $this->listener->endExecute($id);

        return $result;
    }

    public function query(string $statement): StatementWrapper
    {
        $id = $this->listener->startExecute($statement);

        $result = $this->pdo->query($statement);

        $this->listener->endExecute($id);

        return new EmittingStatement($result, $this->listener);
    }
}
