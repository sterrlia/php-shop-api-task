<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Entity\Product;

final readonly class ProductRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function getByUuid(string $uuid): ?Product
    {
        $row = $this->connection->fetchOne(
            'SELECT * FROM products WHERE uuid = :uuid',
            [
                'uuid' => $uuid,
            ]
        );

        if (empty($row)) {
            return null;
        }

        return $this->make($row);
    }

    /** @return Product[] */
    public function fetchByUuids(string ...$uuid): array
    {
        $rows = $this->connection->fetchOne(
            'SELECT * FROM products WHERE uuid in (:uuid)',
            [
                'uuids' => $uuid,
            ]
        );

        return array_map(
            static fn (array $row) => $this->make($row),
            $rows
        );
    }

    // :TODO: можно сделать фильтр обьект и метод getByFilter на каждый тип данных (одна сущность, массив сущностей, генератор)
    /** @return Product[] */
    public function getActiveByCategory(string $category): array
    {
        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM products WHERE is_active = 1 AND category = :category',
            [
                'category' => $category,
            ]
        );

        return array_map(
            static fn (array $row) => $this->make($row),
            $rows
        );
    }

    // :TODO: лучше использовать symfony serializer либо orm
    /** @param mixed[] $row */
    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
