<?php
namespace App\Services\Alert;

use App\Models\User;
use App\Models\Alert;
use Illuminate\Http\Request;
use App\Models\GovernmentOffice;
use App\Events\ForwardingCreated;
use App\Abstracts\AbstractService;
use App\Infra\Repository\Alert\ForwardingRepository;
use App\Models\Record;

class ForwardingService extends AbstractService
{
    /**
     * @var ForwardingRepository
     */
    protected $repository;

    public function __construct(ForwardingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function beforeSave(Request $request): array
    {             
        $requestForwarding = array_map(function($item) {
            return [
                'user_id' => User::findByUUID($item['user_id'])->id,
                'record_id' => Record::findByUUID($item['record_id'])->id,  
                'email' => GovernmentOffice::findByUUID($item['government_office_id'])->email,
                'description' => $item['description']                  
            ];
        }, array($request->all()));
        
        $request->merge(end($requestForwarding));
               
        return $request->toArray();
    }

    /**
     * @param Request $request
     * @param mixed $model
     * @return void
     */
    public function afterSave(Request $request, mixed $model): void
    {
        event(new ForwardingCreated($model));
    }
}
