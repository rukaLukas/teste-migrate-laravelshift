<?php

namespace App\Infra\Repository;

use App\Models\TargetPublic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use App\Models\AlertStep;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Database\Eloquent\Model;

class StatusAlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(StatusAlert $model)
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
