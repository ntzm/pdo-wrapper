<?php declare(strict_types=1);

namespace PdoWrapper\Thin;

use InvalidArgumentException;
use PDO;
use PdoWrapper\PdoWrapper;
use PdoWrapper\StatementWrapper;

final class ThinPdo implements PdoWrapper
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        if ($pdo->getAttribute(PDO::ATTR_ERRMODE) !== PDO::ERRMODE_EXCEPTION) {
            throw new InvalidArgumentException('PDO must have ATTR_ERRMODE set to ERRMODE_EXCEPTION');
        }

        $this->pdo = $pdo;
    }

    public function prepare(string $statement): StatementWrapper
    {
        return new ThinStatement($this->pdo->prepare($statement));
    }

    public function exec(string $statement): int
    {
        return $this->pdo->exec($statement);
    }

    public function query(string $statement): StatementWrapper
    {
        return new ThinStatement($this->pdo->query($statement));
    }
}
