<?php

namespace App\Abstracts;

use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NaoEncontradaException;
use phpDocumentor\Reflection\Types\Object_;
use App\Interfaces\Service\ServiceInterface;
use App\Exceptions\GeneralValidationException;
use App\Interfaces\Repository\RepositoryInterface;

/**
 * Class AbstractService
 * @package Arquitetura\Infra\Service
 */
abstract class AbstractService implements ServiceInterface
{
    /**
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var Model
     */
    protected $entity;


    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param string|int $id
     */
    public function delete(string|int $id): void
    {
        $this->beforeDelete($id);
        $this->repository->delete($id);
    }

    /**
     * @param Request $request
     * @return Model
     */
    public function save(Request $request, $validationName = null): Model
    {
        $this->validate($request, 'save', $validationName);
        $params = $this->beforeSave($request);
        $model = $this->repository->save($params);
        $this->afterSave($request, $model);

        return $model;
    }

    public function find(string|int $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * @param Request $request
     * @param string|int $id
     * @return Model
     */
    public function update(Request $request, string|int $id, $validationName = null): Model
    {
        $request['id'] = $id;
        $this->validate($request, 'update', $validationName);
        $this->entity = $this->getRepository()->find($id);
        $data = $this->beforeUpdate($request, $id);

        if (null === $this->entity) {
            throw new NaoEncontradaException($id);
        }

        $this->repository->update($this->entity, $data);
        $this->afterUpdate($request, $this->entity);

        return $this->find($id);
    }

    /**
     * validate function
     *
     * @param Request $request
     * @param string $type
     * @param string $validationName
     * @return void
     */
    public function validate(Request $request, string $type, string $validationName = null): void
    {
        $validationClass = "App\\Validations\\{$validationName}\\{$validationName}EnabledTo" . ucfirst($type);
        if (!is_null($validationName) && class_exists($validationClass)) {
            $validation = new $validationClass();
            $modelClass = "App\\Models\\{$validationName}";
            $model = new $modelClass($request->all());
            throw_if(
                !$validation->validate($model)->isValid(),
                new GeneralException($validation->getErrors())
            );
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function beforeSave(Request $request): array
    {
        return $request->all();
    }

    /**
     * @param Request $request
     * @param mixed $model
     * @return void
     */
    public function afterSave(Request $request, mixed $model): void
    {

    }

    /**
     * @param Request $request
     * @param mixed $model
     */
    public function afterUpdate(Request $request, mixed $model): void
    {

    }

    /**
     * @param Request $request
     * @param int|string $id
     * @return array
     */
    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        return array_merge($request->all(), ['id' => $id]);
    }

    /**
     * @param string|int $id
     */
    public function beforeDelete(string|int $id): void
    {
    }

    public function getPaginationList(array $params)
    {
        return $this->getRepository()->getPaginationList($params);
    }

    /**
     * Pre Requisite default
     */
    public function preRequisite($id = null)
    {

    }
}
