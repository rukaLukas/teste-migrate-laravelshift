<?php

namespace App\Interfaces\Service;

use App\Interfaces\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ServiceInterface
{
    /**
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;

    /**
     * @param Request $request
     * @return Model
     */
    public function save(Request $request): Model;

    /**
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * @param Request $request
     */
    public function validate(Request $request, string $type, string $validationName = null): void;

    /**
     * @param Request $request
     * @return array
     */
    public function beforeSave(Request $request): array;

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function beforeUpdate(Request $request, int $id): array;

}
