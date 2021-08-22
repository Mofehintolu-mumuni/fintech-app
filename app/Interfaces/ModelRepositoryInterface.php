<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface  ModelRepositoryInterface {
    public function findById(int $resourceId): Object|null;

    public function create(array $data): Object|null;

    public function delete(int $resourceId): bool;

    public function update(int $resourceId, array $data): bool;

    public function getAll() : Collection;

    public function getById(array $arrayOfIds) : Collection;
}