<?php declare(strict_types=1);

namespace PdoWrapper\Emitting;

final class QueryId
{
    /** @var string */
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): self
    {
        return new self(uniqid('', true));
    }

    public function asString(): string
    {
        return $this->id;
    }
}
