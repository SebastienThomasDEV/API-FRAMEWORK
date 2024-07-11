<?php

namespace Sthom\Back\Annotations\db;

/**
 * This file is used to define the column types to match code types to SQL types
 */
enum ColumnType
{
    const STRING = 'string';
    const INT = 'integer';
    const BOOL = 'boolean';

    const DATE = 'date';

    const DATETIME = 'datetime';

    const TIMESTAMP = 'timestamp';

    const TEXT = 'text';

    const FLOAT = 'float';

    const DOUBLE = 'double';

    const DECIMAL = 'decimal';

    const ENUM = 'enum'; // You might need additional handling for ENUM values

    const SET = 'set';   // You might need additional handling for SET values

    const JSON = 'json';

    const JSONB = 'jsonb';

    const BLOB = 'blob';

    public static function match(string $type): string
    {
        return match ($type) {
            self::STRING => 'string',
            self::INT => 'integer',
            self::BOOL => 'boolean',
            self::DATE => 'date',
            self::DATETIME => 'datetime',
            self::TIMESTAMP => 'timestamp',
            self::TEXT => 'text',
            self::FLOAT => 'float',
            self::DOUBLE => 'double',
            self::DECIMAL => 'decimal',
            self::ENUM => 'enum',
            self::SET => 'set',
            self::JSON => 'json',
            self::JSONB => 'jsonb',
            self::BLOB => 'blob',
        };
    }
}

