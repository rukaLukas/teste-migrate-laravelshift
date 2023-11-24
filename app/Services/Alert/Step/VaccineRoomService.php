<?php
namespace App\Services\Alert\Step;

use App\Models\Alert;
use App\Models\Vaccine;
use App\Models\StatusAlert;
use Illuminate\Http\Request;
use App\Abstracts\AbstractService;
use App\Models\AlertStep;
use App\Models\DelayedVaccine;
use App\Models\NotAppliedVaccine;
use App\Services\AlertStepService;
use App\Models\VaccineScheduledAlert;
use App\Services\VaccineScheduledAlertService;
use Illuminate\Database\Eloquent\Model;

class VaccineRoomService extends AbstractService
{
    /**
     * @var AlertStepService
     */
    protected $alertStepService;

    public function __construct(AlertStepService $alertStepService)
    {
        $this->alertStepService = $alertStepService;
    }

    public function save(Request $request, $validationName = null): Model
    {                   
        $alert = Alert::findByUUID($request['alert_id']);
        $this->saveAppliedVaccines($request, $alert->id);      
                 
        $appliedVaccines = array_map(function($vaccine) {            
            return Vaccine::findByUUID($vaccine['vaccine_id'])->id;
        }, $request->applied_vaccines);
        
        
        // remove previous delayed vaccines marked in last tech analysis step
        $alertStepTechAnalysis = $alert->record->alertSteps
            ->where('status_alert_id', StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ANALISE_TECNICA])->first()->id)
            ->first();
        DelayedVaccine::where('alert_step_id', $alertStepTechAnalysis->id)
            ->whereIn('vaccine_id', $appliedVaccines)->delete();
           
        $doneVaccination = $this->isVaccinationComplete($alertStepTechAnalysis, $request); 
        $request['step'] = $doneVaccination == true ?
            StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::CONCLUIDO])->first()->uuid :
            StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ENCAMINHAMENTO])->first()->uuid; 
        
        $model = $this->alertStepService->saveStep($request, $validationName);
        
        if (!$request->complete)
            $this->saveNotAppliedVaccines($request, $model->id);

        return $model;        
    }

    private function saveAppliedVaccines(Request $request, int $alertId): void
    {
        foreach($request->applied_vaccines as $vaccine) {
            $objVaccine = Vaccine::findByUUID($vaccine['vaccine_id']);
            
            // check if $objVaccine->dose is less than 3, if true get the next dose
            $nextDose = $objVaccine->dose < 3 ? Vaccine::where('name', $objVaccine->name)->where('dose', $objVaccine->dose + 1)->first() : null;            
            $nextApplicationDate = !is_null($nextDose) ?
                date('Y-m-d', strtotime($vaccine['date_application'] . ' + ' . $nextDose->days_interval . ' days')) :
                null;   
            VaccineScheduledAlert::create([
                'alert_id' => $alertId,
                'vaccine_id' => $objVaccine->id,
                'previous_application' => $vaccine['date_application'],
                'next_application' => $nextApplicationDate,
            ]);
        }
    }

    private function saveNotAppliedVaccines(Request $request, int $stepAlertId): void
    {
        $notAppliedVaccines = $request->not_applied_vaccines;
        foreach($notAppliedVaccines as $notAppliedVaccine) {
            $objVaccine = Vaccine::findByUUID($notAppliedVaccine['vaccine_id']);      
            NotAppliedVaccine::create([
                'alert_step_id' => $stepAlertId,
                'vaccine_id' => $objVaccine->id                
            ]);
        }
    }

    private function isVaccinationComplete(AlertStep $alertStep, $request): bool
    {
        return $this->checkVaccinationComplete($request, $alertStep->id);                
    } 

    /**
     * check if all eligible delayed vaccines were applied in vaccine room
     *
     * @param [type] $request
     * @return boolean
     */
    private function checkVaccinationComplete($request, int $alertStepId): bool
    {
        $vacsNotApplied = [];
        $namesToExclude = [];

        $delayedVaccines = DelayedVaccine::where('alert_step_id', $alertStepId)->first();
        if ($delayedVaccines == null) {
            return true;
        }

        return false;

        // DelayedVaccine::where('alert_step_id', $alertStepId)->get()->each(function($vaccine) use ($request, &$vacsNotApplied, &$namesToExclude) {
                        
        //     $vac = Vaccine::find($vaccine->vaccine_id);            
        //     if ($vac->dose != 1) {            
        //         $vac2 = Vaccine::where('name', $vac->name)
        //             ->where('target_public_id', $vac->target_public_id)
        //             ->where('dose', '=', --$vac->dose)
        //             ->first();
        
        //         if (array_search($vac2->uuid, array_column($request['applied_vaccines'], 'vaccine_id')) !== false) {
        //             $namesToExclude[] = $vac2->name;
        //         }
        //         else {
        //             if (!in_array($vac2->name, $namesToExclude)) {
        //                 $vacsNotApplied[] = $vaccine->vaccine_id;                        
        //             }                                        
        //         }                 
        //     } else {
        //         $vacsNotApplied[] = $vaccine->vaccine_id;
        //     }
        // });
        
        // return count($vacsNotApplied) > 0 ? false : true;
    }
}
