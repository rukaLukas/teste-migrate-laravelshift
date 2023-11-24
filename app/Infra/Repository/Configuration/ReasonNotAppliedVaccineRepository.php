<?php
namespace App\Infra\Repository\Configuration;

use App\Abstracts\AbstractRepository;
use App\Models\ReasonNotAppliedVaccine;
use Illuminate\Database\Eloquent\Model;

class ReasonNotAppliedVaccineRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(ReasonNotAppliedVaccine $model)
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
