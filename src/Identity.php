<?php
declare(strict_types=1);

namespace Szabacsik\Identity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class Identity
{
    public $uuid;

    private function __construct(?string $anUuid = null)
    {
        $this->uuid = is_null($anUuid) ? Uuid::uuid4()->toString() : $anUuid;
    }

    public static function create(?string $anUuid = null): Identity
    {
        if ($anUuid)
            if (!self::isValid($anUuid))
                throw new InvalidUuidStringException();
        return new static($anUuid);
    }

    public static function isValid(string $anUuid): bool
    {
        $valid = false;
        try {
            $uuid = Uuid::fromString($anUuid);
            $version = $uuid->getFields()->getVersion();
            $valid = ($version === 4);
        } catch (InvalidUuidStringException $e) {
            $valid = false;
        }
        return $valid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}