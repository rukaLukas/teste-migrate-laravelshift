<?php

namespace App\Infra\Repository;

use App\Abstracts\AbstractRepository;
use App\Models\CaseStep;
use Illuminate\Database\Eloquent\Model;

class CaseStepRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(CaseStep $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {        
        return [
            'uuid' => $this->getAttribute($params, 'uuid'),
            'user_id' => $this->getAttribute($params, 'user_id'),
            'alert_id' => $this->getAttribute($params, 'alert_id'),
            'is_alert' => $this->getAttribute($params, 'is_alert'),
            'is_analysis' => $this->getAttribute($params, 'is_analysis'),
            'is_forwarded' => $this->getAttribute($params, 'is_forwarded'),
            'is_vaccineroom' => $this->getAttribute($params, 'is_vaccineroom'),
            'is_done' => $this->getAttribute($params, 'is_done'),
            'is_closed' => $this->getAttribute($params, 'is_closed')
        ];
    }
}
