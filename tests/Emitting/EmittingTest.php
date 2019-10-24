<?php declare(strict_types=1);

namespace PdoWrapperTest\Emitting;

use InvalidArgumentException;
use PDO;
use PdoWrapper\Emitting\EmittingPdo;
use PdoWrapper\Emitting\Listener;
use PdoWrapper\Emitting\QueryId;
use PHPUnit\Framework\TestCase;

final class EmittingTest extends TestCase
{
    /** @var PDO */
    private $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function testPrepareExecuteWithBindValue(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT * FROM users WHERE id = :id', ['id' => 5])
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $this->pdo->exec('CREATE TABLE users (id INT)');
        $this->pdo->exec('INSERT INTO users (id) VALUES (5)');

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->bindValue('id', 5);
        $statement->execute();
    }

    public function testPrepareExecuteWithArguments(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT * FROM users WHERE id = :id', ['id' => 5])
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $this->pdo->exec('CREATE TABLE users (id INT)');
        $this->pdo->exec('INSERT INTO users (id) VALUES (5)');

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute(['id' => 5]);
    }

    public function testExec(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('DELETE FROM users')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $this->pdo->exec('CREATE TABLE users (id INT)');
        $this->pdo->exec('INSERT INTO users (id) VALUES (5)');

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $result = $emittingPdo->exec('DELETE FROM users');

        $this->assertSame(1, $result);
    }

    public function testQuery(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT 5 AS id')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $result = $emittingPdo->query('SELECT 5 AS id');

        $this->assertSame('5', $result->fetchColumn());
    }

    public function testNonExceptionErrorMode(): void
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $listener = $this->createMock(Listener::class);

        $this->expectException(InvalidArgumentException::class);

        new EmittingPdo($listener, $this->pdo);
    }

    public function testFetchAllWithoutStyle(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT 5 AS id')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT 5 AS id');

        $statement->execute();

        $this->assertSame([
            [
                'id' => '5',
                0 => '5',
            ],
        ], $statement->fetchAll());
    }

    public function testFetchAllWithStyle(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT 5 AS id')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT 5 AS id');

        $statement->execute();

        $this->assertSame([
            [
                'id' => '5',
            ],
        ], $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testFetchWithoutStyle(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT 5 AS id')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT 5 AS id');

        $statement->execute();

        $this->assertSame([
            'id' => '5',
            0 => '5',
        ], $statement->fetch());
    }

    public function testFetchWithStyle(): void
    {
        $id = QueryId::create();

        $listener = $this->createMock(Listener::class);

        $listener
            ->expects($this->once())
            ->method('startExecute')
            ->with('SELECT 5 AS id')
            ->willReturn($id);

        $listener
            ->expects($this->once())
            ->method('endExecute')
            ->with($id);

        $emittingPdo = new EmittingPdo($listener, $this->pdo);
        $statement = $emittingPdo->prepare('SELECT 5 AS id');

        $statement->execute();

        $this->assertSame([
            'id' => '5',
        ], $statement->fetch(PDO::FETCH_ASSOC));
    }
}
