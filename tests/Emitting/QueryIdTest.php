<?php declare(strict_types=1);

namespace PdoWrapperTest\Emitting;

use PdoWrapper\Emitting\QueryId;
use PHPUnit\Framework\TestCase;

final class QueryIdTest extends TestCase
{
    public function testGeneratesRandom(): void
    {
        $this->assertNotSame(QueryId::create()->asString(), QueryId::create()->asString());
    }
}
