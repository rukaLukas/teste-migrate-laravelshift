<?php
namespace App\Infra\Repository\Alert;

use App\Abstracts\AbstractRepository;
use App\Models\Forwarding;
use Illuminate\Database\Eloquent\Model;

class ForwardingRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Forwarding $model)
    {
        $this->model = $model;
    }

    public function formatParams(array $params): array
    {
        return [
            'user_id' => $this->getAttribute($params, 'user_id'),
            'record_id' => $this->getAttribute($params, 'record_id'),
            'description' => $this->getAttribute($params, 'description'),
            'email' => $this->getAttribute($params, 'email'),
        ];
    }
}
