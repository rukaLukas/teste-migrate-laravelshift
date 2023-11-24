<?php
namespace App\Services\Case;


use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Abstracts\AbstractService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use App\Infra\Repository\AlertRepository;
use App\Validations\Alert\AlertEnabledToSave;
use App\Interfaces\Repository\AlertRepositoryInterface;

class AlertService extends AbstractService
{
    /**
     * @var AlertRepositoryInterface
     */
    protected $repository;

    public function __construct(AlertRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateReasonsDelayVaccine(Request $request, int $id): mixed
    {
        $alert = $this->find($id);
        $reasonsDelayVaccine = array_map(function($item) use ($alert) {                    
            return \App\Models\ReasonDelayVaccine::Where('uuid', '=', $item)->first()->id;
        }, $request->reasons_delay_vaccine);

        $alert->reasonDelayVaccine()->detach();
        $alert->reasonDelayVaccine()->attach($reasonsDelayVaccine); 

        return $alert;
    }
}
