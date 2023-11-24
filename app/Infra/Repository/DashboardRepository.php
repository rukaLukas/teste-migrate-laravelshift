<?php

namespace App\Infra\Repository;

use App\Models\Alert;
use App\Models\TargetPublic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class DashboardRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Alert $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        // TODO: Implement formatParams() method.
    }
}
