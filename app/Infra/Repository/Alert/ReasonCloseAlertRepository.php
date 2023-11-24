<?php
namespace App\Infra\Repository\Alert;

use App\Models\ReasonCloseAlert;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class ReasonCloseAlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(ReasonCloseAlert $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'description' => $this->getAttribute($params, 'description')
        ];
    }
}
