<?php

namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\Deadline;
use Illuminate\Database\Eloquent\Model;

class DeadlineRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Deadline $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'name' => $this->getAttribute($params, 'name'),
            'days' => $this->getAttribute($params, 'days'),            
        ];
    }
}