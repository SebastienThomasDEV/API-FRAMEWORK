<?php

namespace Sthom\Back\Kernel\Framework\Annotations\Mapper;



/**
 * Classe Field pour définir les champs des entités de l'application.
 *
 *
 * @package Sthom\Back\Kernel\Framework\Annotations
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column
{

    private string $name;
    private string $type;
    private bool $nullable;
    private array $options;
    private bool $primary;

    public function __construct(string $name, string $type, bool $nullable = false, array $options = [])
    {
        $this->name = $name;
        $this->type = $this->checkType($type);
        $this->nullable = $nullable;
        $this->primary = $name === 'id';
        $this->checkOptions($options);
        $this->options = $options;
    }

    public final function getName(): string
    {
        return $this->name;
    }

    public final function getType(): string
    {
        return $this->type;
    }

    public final function isNullable(): bool
    {
        return $this->nullable;
    }


    public final function isPrimary(): bool
    {
        return $this->primary;
    }

    public final function getOptions(): array
    {
        return $this->options;
    }

    private function checkOptions(array $options): void
    {
        if (isset($options['length']) && !is_int($options['length'])) {
            throw new \InvalidArgumentException('Length must be an integer');
        }
        if (isset($options['unique']) && !is_bool($options['unique'])) {
            throw new \InvalidArgumentException('Unique must be a boolean');
        }
        if (isset($options['default']) && !is_string($options['default'])) {
            throw new \InvalidArgumentException('Default must be a string');
        }
        if (isset($options['autoincrement']) && !is_bool($options['autoincrement'])) {
            throw new \InvalidArgumentException('Autoincrement must be a boolean');
        }
        if (isset($options['precision']) && !is_int($options['precision'])) {
            throw new \InvalidArgumentException('Precision must be an integer');
        }
        if (isset($options['scale']) && !is_int($options['scale'])) {
            throw new \InvalidArgumentException('Scale must be an integer');
        }
        if (isset($options['comment']) && !is_string($options['comment'])) {
            throw new \InvalidArgumentException('Comment must be a string');
        }
        if (isset($options['collation']) && !is_string($options['collation'])) {
            throw new \InvalidArgumentException('Collation must be a string');
        }
        if (isset($options['charset']) && !is_string($options['charset'])) {
            throw new \InvalidArgumentException('Charset must be a string');
        }
        if (isset($options['unsigned']) && !is_bool($options['unsigned'])) {
            throw new \InvalidArgumentException('Unsigned must be a boolean');
        }
    }

    private function checkType(string $type): string
    {
        if (!in_array($type, ['int', 'string', 'float', 'bool', 'datetime'])) {
            throw new \InvalidArgumentException('Invalid type');
        } else {
            return $type;
        }
    }



}
