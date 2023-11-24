<?php
namespace App\Services\Configuration;

use App\Models\User;
use App\Models\County;
use Illuminate\Http\Request;
use App\Models\GovernmentOffice;
use App\Abstracts\AbstractService;
use App\Models\ReasonDelayVaccine;
use App\Infra\Repository\Configuration\TargetPublicRepository;
use App\Infra\Repository\Configuration\ReasonsVaccineDelayRepository;

class ReasonsVaccineDelayService extends AbstractService
{
    /**
     * @var TargetPublicRepository
     */
    protected $repository;

    protected $targetPublicService;

    public function __construct(
        ReasonsVaccineDelayRepository $repository,
        TargetPublicService $targetPublicService
    )
    {
        $this->repository = $repository;
        $this->targetPublicService = $targetPublicService;
    }

    /**
     * getByCounty function
     *
     * @param integer $id
     * @return mixed
     */
    public function getByCounty(int $id): mixed
    { 
        $entity = ReasonDelayVaccine::get()->paginate();
        $entity->map(function ($itemEntity) use ($id, &$achados) {
            $itemEntity->governmentOffices = $itemEntity->governmentOffices->filter(function ($go) use ($id, &$achados) {
                $county = County::find($id);
                return $go->county_id == $county->id;                                       
            });
        });

        return $entity;
    }   

    public function beforeSave(Request $request): array
    {
        $data = $request->all();
        $uuid = data_get($data, 'target_public_id');
        $data['target_public_id'] = $this->targetPublicService->find($uuid)->id;
        return $data;
    }

    public function beforeUpdate(Request $request, int|string $id, string $validationName = null): array
    {
        $data = $request->all();
        $uuid = data_get($data, 'target_public_id');
        $data['target_public_id'] = $this->targetPublicService->find($uuid)->id;
        return $data;
    }

    /**
     * @return mixed
     */
    public function preRequisite($id = null)
    {
        $arr['targetPublic'] = generateSelectOption($this->targetPublicService->getRepository()->list());
        return $arr;
    }

    public function getByTargetPublic(string $targetPublicUUID)
    {
        $targetPublicId = $this->targetPublicService->find($targetPublicUUID)->id;
        return $this->repository->getByTargetPublic($targetPublicId);
    }

    /**
     * bindGovernmentOfficeReasonDelayVaccine function
     *
     * @param string $userId
     * @param string $reasonDelayVaccineId
     * @param array $governmentOffices
     * @return void
     */    
    public function bindGovernmentOfficeReasonDelayVaccine(int $countyId, string $reasonDelayVaccineId, array $governmentOfficesId)
    {        
        $governmentOffices = GovernmentOffice::Where('county_id', $countyId)->get();                                
        
        foreach ($governmentOffices as $governmentOffice) {
            $governmentOffice->reasonDelayVaccines()->detach(ReasonDelayVaccine::findByUUID($reasonDelayVaccineId)->id);
        }
        
        $governmentOffices = GovernmentOffice::WhereIn('uuid', $governmentOfficesId)->get();
        foreach ($governmentOffices as $governmentOffice) {
            $governmentOffice->reasonDelayVaccines()->attach(ReasonDelayVaccine::findByUUID($reasonDelayVaccineId)->id);
        }
    }
}
