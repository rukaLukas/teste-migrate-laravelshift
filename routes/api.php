<?php

use App\Http\Controllers\AccessionController;
use App\Http\Controllers\Alert\ClosedAlertController;
use App\Http\Controllers\Alert\ForwardingController;
use App\Http\Controllers\Alert\ReasonCloseAlertController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AlertStepController;
use App\Http\Controllers\Auth\RecoverPasswordController;
use App\Http\Controllers\Auth\TwoFAController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\CaseStepController;
use App\Http\Controllers\CepController;
use App\Http\Controllers\Configuration\DeadlineController;
use App\Http\Controllers\Configuration\GovernmentOfficeController;
use App\Http\Controllers\Configuration\GovernmentOfficeUserController;
use App\Http\Controllers\Configuration\GroupController;
use App\Http\Controllers\Configuration\GroupUserController;
use App\Http\Controllers\Configuration\MenuController;
use App\Http\Controllers\Configuration\MenuCountyController;
use App\Http\Controllers\Configuration\MenuOccupationController;
use App\Http\Controllers\Configuration\OccupationController;
use App\Http\Controllers\Configuration\ReasonNotAppliedVaccineController;
use App\Http\Controllers\Configuration\ReasonsVaccineDelayController;
use App\Http\Controllers\Configuration\SubGroupController;
use App\Http\Controllers\Configuration\SubGroupUserController;
use App\Http\Controllers\Configuration\TargetPublicController;
use App\Http\Controllers\Configuration\UnderSubGroupController;
use App\Http\Controllers\Configuration\UnderSubGroupUserController;
use App\Http\Controllers\Configuration\VaccineController;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StatusAlertController;
use App\Http\Controllers\TypeStausVaccinationController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaccineRoomController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;


$limiter = config('fortify.limiters.login');
Route::post('auth/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(
        array_filter(['guest', $limiter ? 'throttle:' . $limiter : null])
    )->name('login');


Route::get('auth/me', [AuthController::class, 'me']);

Route::get('/health', function () {
    return ['up' => true];
});

Route::middleware('selo')->group(function () {
    Route::get('/accession/{siglaMunicipio}/selo', [AccessionController::class, 'selo']);
});


Route::post('auth/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('auth/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store']);
Route::post('auth/recover-password', [RecoverPasswordController::class, 'recoverPassword'])->name("recover.password");
Route::post('auth/reset-password', [RecoverPasswordController::class, 'resetPassword'])->name("password.reset");

Route::group(['prefix' => '/accession'], function () {
    Route::post('register', [AccessionController::class, 'register']);
    Route::get('pre-requisite', [AccessionController::class, 'preRequisite']);
    Route::get('validate-has-county/{id}', [AccessionController::class, 'validateHasCounty']);
    Route::get('/{id}/user/{userId}/validate', [AccessionController::class, 'validateAccessionUser']);
    Route::get('/{id}/user/{userId}/validate-password', [AccessionController::class, 'validateAccessionUserPassword']);
    Route::put('/{id}/user/{userId}/confirm-external', [AccessionController::class, 'confirmExternal']);
    Route::put('/{id}/user/{userId}/confirm-external-password', [AccessionController::class, 'confirmExternalPassword']);
});

Route::get('/users/pre-requisite', [UserController::class, 'preRequisite']);
Route::get('county/get-by-state/{id}', [CountyController::class, 'getByState']);
Route::get('state/to-select', [StateController::class, 'toSelect']);

Route::post('users', [UserController::class, 'store']);
Route::put('/users/{userId}/confirm-external', [UserController::class, 'confirmExternal']);
Route::get('/users/{userId}/validate-already-made-accession', [UserController::class, 'validateAlreadyMadeAccession']);

Route::middleware('auth:sanctum')->group(
    function () {

        Route::get('get-cep/{cep}', [CepController::class, 'getCep']);

        Route::post('auth/users/confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::get('auth/users/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show']);
        Route::apiResource('profiles', ProfileController::class);
        Route::apiResource('target-publics', TargetPublicController::class);
        Route::post('upload-image', [UploadController::class, 'uploadImage']);

        // COnfiguration
        Route::get('reasons-vaccine-delay/pre-requisite', [ReasonsVaccineDelayController::class, 'preRequisite']);
        Route::apiResource('reasons-vaccine-delay', ReasonsVaccineDelayController::class);
        Route::get('reasons-vaccine-delay/target-public/{id}', [ReasonsVaccineDelayController::class, 'targetPublic']);
        Route::get('reasons-vaccine-delay/county/{id}', [ReasonsVaccineDelayController::class, 'getByCounty']);
        Route::put('reasons-vaccine-delay/county/{id}', [ReasonsVaccineDelayController::class, 'bindGovernmentOfficeReasonDelayVaccineUser']);

        Route::apiResource('menus', MenuController::class);


        Route::post('menu-counties/add-to-all-states', [MenuCountyController::class, 'addToAllStates']);
        Route::post('menu-counties/add-to-all-counties/state/{id}', [MenuCountyController::class, 'addToAllCounties']);
        Route::apiResource('menu-counties', MenuCountyController::class);

        Route::apiResource('menu-occupations', MenuOccupationController::class);
        Route::get('/occupations/to-select', [OccupationController::class, 'toSelect']);

        Route::apiResource('vaccines', VaccineController::class);
        Route::apiResource('deadline', DeadlineController::class);
        Route::get('government-offices/default', [GovernmentOfficeController::class, 'default']);
        Route::get('government-offices/pre-requisite/{id}', [GovernmentOfficeController::class, 'preRequisite']);
        Route::get('government-office-users/user/{id}', [GovernmentOfficeUserController::class, 'getByUser']);
        Route::apiResource('government-offices', GovernmentOfficeController::class);
        Route::get('government-offices/county/{id}', [GovernmentOfficeController::class, 'getByCounty']);
        Route::apiResource('reason-close-alerts', ReasonCloseAlertController::class);
        Route::apiResource('breeds', BreedController::class);
        Route::apiResource('genres', GenreController::class);
        Route::apiResource('vaccine-rooms', VaccineRoomController::class);
        Route::apiResource('type-status-vaccinations', TypeStausVaccinationController::class);

        Route::get('/groups/get-by-county/{countyId}', [GroupController::class, 'getByCounty']);
        Route::post('/groups/move', [GroupController::class, 'move']);
        Route::get('/groups/{id}/users', [GroupController::class, 'getUsers']);
        Route::get('/groups/to-select', [GroupController::class, 'toSelect']);
        Route::apiResource('groups', GroupController::class);


        Route::get('/sub-groups/{id}/users', [SubGroupController::class, 'getUsers']);
        Route::apiResource('sub-groups', SubGroupController::class);

        Route::get('/under-sub-groups/{id}/users', [UnderSubGroupController::class, 'getUsers']);
        Route::get('under-sub-groups/pre-requisite', [UnderSubGroupController::class, 'preRequisite']);
        Route::post('under-sub-groups/move', [UnderSubGroupController::class, 'move']);
        Route::apiResource('under-sub-groups', UnderSubGroupController::class);

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/total-alerts', [DashboardController::class, 'totalAlerts']);
        });

        Route::post('/users/{id}/resend-mail-accession', [UserController::class, 'resendMailAccession']);
        Route::post('/users/avatar', [UserController::class, 'avatar']);
        Route::apiResource('/users', UserController::class);

        Route::apiResource('/group-users', GroupUserController::class);
        Route::apiResource('/sub-group-users', SubGroupUserController::class);
        Route::apiResource('/under-sub-group-users', UnderSubGroupUserController::class);

        // Alerts
        Route::group(['prefix' => '/alerts'], function () {
            Route::group(['prefix' => '/step/{id}'], function () {
                Route::post('technical-analysis', [AlertStepController::class, 'technicalAnalysis']);
                Route::post('start-technical-analysis', [AlertStepController::class, 'startTechnicalAnalysis']);
            });

            Route::get('/', [AlertController::class, 'index']);
            Route::get('list', [AlertController::class, 'list']);
            Route::get('/{id}', [AlertController::class, 'show']);
            Route::post('/', [AlertController::class, 'save']);
            Route::post('/{id}/step', [AlertController::class, 'step']);
            Route::post('/step/vaccine-room', [AlertStepController::class, 'vaccineRoom']);
            Route::put('/{id}', [AlertController::class, 'updateAlert']);
            Route::put('/{id}/reasons-vaccine-delay', [AlertController::class, 'updateReasonsDelayVaccine']);
            Route::delete('/{id}', [AlertController::class, 'destroy']);
        });

        Route::apiResource('/records', RecordController::class);
        Route::apiResource('/status-alerts', StatusAlertController::class);


        Route::get('/reason-not-applied-vaccines', [ReasonNotAppliedVaccineController::class, 'index']);
        Route::apiResource('closed-alerts', ClosedAlertController::class);
        Route::apiResource('forwardings', ForwardingController::class);
        //Route::apiResource('pending-alerts', PendingAlertController::class);
        //Route::get('/pending-alerts/search/user', [PendingAlertController::class, 'search']);
        // Route::get('/pending-alerts/search/{term}', [PendingAlertController::class, 'search']);

        // Alert Case
        Route::apiResource('/alert-case', CaseStepController::class);
        Route::post('/alert-case/assign-to', [CaseStepController::class, 'assignTo']);
        Route::post('/alert-case/start-technical-analysis', [CaseStepController::class, 'startTechnicalAnalysis']);

        // Accessions
        Route::group(['prefix' => '/accession'], function () {
            Route::put('{id}/confirm', [AccessionController::class, 'confirm']);
            Route::put('{id}/reject', [AccessionController::class, 'reject']);

        });

        Route::apiResource('accession', AccessionController::class);

        // Geolocation
        Route::group(['prefix' => '/geolocation'], function () {
            Route::get('postalcode/{postalCode}', [GeolocationController::class, 'addressByPostalCode']);
            Route::get('reverse-geocode/{latlng}', [GeolocationController::class, 'addressByLatLng']);
        });

        if (Features::enabled(Features::twoFactorAuthentication())) {
            $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? ['password.confirm']
                : [];

            Route::post('auth/user/two-factor-authentication', [TwoFAController::class, 'enable'])
                ->middleware($twoFactorMiddleware);

            Route::delete('auth/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
                ->middleware($twoFactorMiddleware);

            Route::get('auth/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
                ->middleware($twoFactorMiddleware);

            Route::get('auth/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
                ->middleware($twoFactorMiddleware);

            Route::post('auth/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
                ->middleware($twoFactorMiddleware);
        }

    }
);
