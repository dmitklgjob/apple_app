<?php

declare(strict_types=1);

namespace Apple\Domain;

interface AppleRepositoryInterface
{
    public function save(Apple $apple): void;

    public function delete(Apple $apple): void;

    public function get(int $id): Apple;

    public function find(int $id): ?Apple;

    public function checkGrowingApples(): void;

    public function checkRottenApples(): void;
}
