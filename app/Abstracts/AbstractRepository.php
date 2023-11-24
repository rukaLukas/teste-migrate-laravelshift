<?php

namespace App\Abstracts;

use App\Interfaces\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class AbstractRepository implements RepositoryInterface
{

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param null $params
     * @param array|null $with
     * @param string $orderBy
     * @return mixed
     */
    public function all($params = null, ?array $with = [], string $orderBy = 'id'): mixed
    {
        return $this->getModel()->with($with)->query($params)->orderBy($orderBy)->get();
    }

    /**
     * Retorna em forma de lista para selecte
     * @return mixed
     */
    public function list($sortBy = 'name', $pluck = 'name'): array
    {
        return $this->getModel()->all()->sortBy($sortBy)->pluck($pluck, 'uuid')->all();
    }

    /**
     * @param array $params
     * @return Model
     */
    public function save(array $params): Model
    {
        return $this->getModel()->forceCreate($this->formatParams($params));
    }

    /**
     * @param int|string $id
     * @param array $with
     * @return Model|null
     */
    public function find(int|string $id, array $with = []) :Model|null
    {
        if (is_numeric($id)) {
            return $this->getModel()->with($with)->find($id);
        }
        return $this->findOneWhere(['uuid' => $id]);
    }

    /**
     * Retorna o primeiro registro encontrado
     * @param array $where
     * @return mixed
     */
    public function findOneWhere(array $where): mixed
    {
        $object = $this->where($where);
        return $object->first();
    }

    public function where(array $where, $with = []): mixed
    {
        return $this->getModel()->where($where)->with($with)->get();
    }

    public function deleteWhere(array $where) :void
    {
        $entities = $this->where($where);
        foreach ($entities as $entity) {
            $this->delete($entity->id);
        }
    }

    /**
     * @param string $id
     * @param array $with
     * @return Model
     */
    public function findByUUID(string $id) :Model|null
    {
        return $this->getModel()->findByUUID($id);
    }

    /**
     * @param string|int $id
     */
    public function delete(string|int $id) :void
    {
        $entity = $this->find($id);
        $entity->delete();
    }

    /**
     * @param Model $entity
     * @param array $data
     * @return bool
     */
    public function update(Model $entity, array $data): bool
    {
        return $entity->forceFill($this->formatParams($data))->update();
    }

    /**
     * @param array $params
     * @param string $value
     * @param null $default
     * @return mixed|null
     */
    public function getAttribute(array $params, string $value, $default = null): mixed
    {
        return (isset($params[$value])) ? $params[$value] : $default;
    }

    /**
     * @param array $params
     * @param array $with
     * @return mixed
     */
    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {
        $perPage = $params['per_page'] ?? $params['perPage'] ?? $perPage;
        if ($perPage)
            return $this->getModel()->with($with)->query($params)->paginate($perPage)->withQueryString();

        return $this->getModel()->with($with)->query($params)->paginate()->withQueryString();
    }

    public function whereNotIn(string $field, array $value)
    {
        return $this->getModel()->whereNotIn($field, $value)->get();
    }

    public function whereIn(string $field, array $value)
    {
        return $this->getModel()->whereIn($field, $value)->get();
    }
}
