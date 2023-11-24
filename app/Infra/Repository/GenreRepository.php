<?php

namespace App\Infra\Repository;

use App\Models\Genre;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class GenreRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Genre $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name')
        ];
    }
}
