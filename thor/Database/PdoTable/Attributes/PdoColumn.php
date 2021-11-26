<?php

namespace Thor\Database\PdoTable\Attributes;

use Attribute;
use JetBrains\PhpStorm\Pure;

/**
 * Describe a PdoColumn attribute. Use this attribute on a PdoRowInterface implementor
 * to specify a column from which read and which to write data in the database.
 *
 * @package          Thor/Database/PdoTable
 * @copyright (2021) Sébastien Geldreich
 * @license          MIT
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class PdoColumn
{

    /**
     * @var callable
     */
    private $toSqlValue;

    /**
     * @var callable
     */
    private $toPhpValue;

    public function __construct(
        private string $name,
        private string $sqlType,
        private string $phpType,
        private bool $nullable = true,
        private mixed $defaultValue = null,
        ?callable $toSqlValue = null,
        ?callable $toPhpValue = null
    ) {
        $this->toSqlValue = $toSqlValue;
        $this->toPhpValue = $toPhpValue;
    }

    /**
     * Transforms the $sqlValue to a PHP value with this object callable.
     */
    public function toPhp(mixed $sqlValue): mixed
    {
        return null === $this->toPhpValue ?
            $sqlValue :
            ($this->toPhpValue)($sqlValue);
    }

    /**
     * Transforms the $phpValue to an SQL value with this object callable.
     */
    public function toSql(mixed $phpValue): mixed
    {
        return null === $this->toSqlValue ?
            $phpValue :
            ($this->toSqlValue)($phpValue);
    }

    /**
     * Gets the defined PHP type.
     */
    public function getPhpType(): string
    {
        return $this->phpType;
    }

    /**
     * Gets the SQL statement to create/alter this column.
     */
    #[Pure]
    public function getSql(): string
    {
        $nullStr = $this->isNullable() ? '' : ' NOT NULL';
        $defaultStr = ($this->getDefault() === null)
            ? ($this->isNullable() ? ' DEFAULT NULL' : '')
            : " DEFAULT {$this->getDefault()}";
        return "{$this->getName()} {$this->getSqlType()}$nullStr$defaultStr";
    }

    /**
     * Returns true if this column is nullable.
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * The default value of this column.
     */
    public function getDefault(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * Gets the name of the SQL column.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the defined SQL type.
     */
    public function getSqlType(): string
    {
        return $this->sqlType;
    }

}
