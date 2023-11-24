<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\Pronoun;
use Illuminate\Database\Eloquent\Model;

class PronounRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Pronoun $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
        ];
    }
}
