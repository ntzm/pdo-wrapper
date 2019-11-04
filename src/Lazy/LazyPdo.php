<?php declare(strict_types=1);

namespace PdoWrapper\Lazy;

use PDO;
use PdoWrapper\PdoWrapper;
use PdoWrapper\StatementWrapper;
use PdoWrapper\Thin\ThinStatement;

final class LazyPdo implements PdoWrapper
{
    /** @var PDO|null */
    private $pdo;

    /** @var string */
    private $dsn;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $password;

    /** @var array */
    private $options;

    public function __construct(string $dsn, ?string $username = null, ?string $password = null, array $options = [])
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }

    public function prepare(string $statement): StatementWrapper
    {
        return new ThinStatement($this->pdo()->prepare($statement));
    }

    public function exec(string $statement): int
    {
        return $this->pdo()->exec($statement);
    }

    public function query(string $statement): StatementWrapper
    {
        return new ThinStatement($this->pdo()->query($statement));
    }

    private function pdo(): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password, $this->options);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }
}
