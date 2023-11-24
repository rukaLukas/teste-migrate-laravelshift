<?php
namespace App\Services;


use App\Infra\Repository\AlertRepository;
use App\Models\Alert;
use App\Models\Record;
use Illuminate\Http\Request;
use App\Abstracts\AbstractService;
use App\Events\UserAssignToCaseEvent;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NaoEncontradaException;
use App\Infra\Repository\CaseStepRepository;
use App\Models\User;

class CaseStepService extends AbstractService
{
    /**
     * @var CaseStepRepository
     */
    protected $repository;

    /**
     * @var AlertRepository
     */
    private $alertRepository;

    public function __construct(CaseStepRepository $repository, AlertRepository $alertRepository)
    {
        $this->repository = $repository;
        $this->alertRepository = $alertRepository;
    }

    public function assignTo(Request $request): Model
    {
        $id = $request['id'];
        $entity = Alert::findByUUID($request['alert_id']);
        $user = User::findByUUID($request['user_id']);
        $data = array_merge($entity->attributesToArray(), ['id' => $entity->id, 'user_id' => $user->id]);

        if (null === $entity) {
            throw new NaoEncontradaException($id);
        }

        $this->alertRepository->update($entity, $data);

        //event(new UserAssignToCaseEvent($entity)); TODO verificar esse evento de atribuição

        return $entity;
    }
}
