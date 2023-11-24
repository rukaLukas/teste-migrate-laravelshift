<?php

namespace App\Services;


use Closure;
use App\Models\User;
use App\Models\Group;
use Ramsey\Uuid\Uuid;
use App\Helper\Number;
use App\Models\Profile;
use App\Models\Deadline;
use App\Models\Accession;
use App\Models\Occupation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GovernmentOffice;
use App\Abstracts\AbstractService;
use App\Models\ReasonDelayVaccine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\SubGroupCreatedEvent;
use App\Mail\Accession\ErrataGestor;
use App\Mail\Accession\RejectGestor;
use App\Models\GovernmentOfficeUser;
use Database\Seeders\DeadlineSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Accession\ApproveGestor;
use App\Mail\Accession\ErrataPrefeito;
use App\Mail\Accession\RegisterGestor;
use App\Mail\Accession\RejectPrefeito;
use App\Mail\Accession\ApprovePrefeito;
use App\Mail\Accession\RegisterPrefeito;
use App\Services\Configuration\GroupService;
use App\Infra\Repository\AccessionRepository;
use App\Services\Configuration\SubGroupService;
use App\Infra\Repository\Configuration\GroupRepository;

class AccessionService extends AbstractService
{
    /**
     * @var AccessionRepository
     */
    protected $repository;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var GroupService
     */
    protected $groupService;

    /**
     * @var array<Closure>
     */
    protected $pendencies = [];

    /**
     * @var StateService
     */
    private $stateService;

    public function __construct(
        AccessionRepository $repository,
        UserService         $userService,
        GroupService        $groupService,
        StateService        $stateService
    )
    {
        $this->repository = $repository;
        $this->userService = $userService;
        $this->groupService = $groupService;
        $this->stateService = $stateService;
    }

    private function validateEmail($params)
    {
        $emailsUsers[] = $params['form2']['email'];
        $emailsUsers[] = $params['form3']['email'];

        foreach ($emailsUsers as $email) {
            $this->validateIfEmailExists($email);
        }

        return $this;
    }

    private function validateCPF($params)
    {
        $cpfsUsers[] = Number::getOnlyNumber($params['form2']['cpf']);
        $cpfsUsers[] = Number::getOnlyNumber($params['form3']['cpf']);

        foreach ($cpfsUsers as $cpf) {
            $this->validateIfCPFExists($cpf);
        }

        return $this;
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function register($params)
    {
        $this->validateEmail($params)
            ->validateCPF($params);
        
        $prefeito = $this->savePrefeito($params);
        $gestorDaPolitica = $this->saveGestorDaPolitica($params);
        $accession = $this->saveAdesao($params, $prefeito, $gestorDaPolitica);
       
        if ($prefeito->cpf === $gestorDaPolitica->cpf) {
            throw new \Exception('O CPF os usuários não podem ser iguais');
        }
        
        Mail::to($gestorDaPolitica)->send(
            new RegisterGestor($accession)
        );

        Mail::to($prefeito)->send(
            new RegisterPrefeito($accession)
        );
        Log::info('registrado adesão', ["prefeito" => $prefeito, "gestor" => $gestorDaPolitica, "accession" => $accession]); 
        return $accession;

    }

    /**
     * @param Accession $accession
     */
    private function checkFluxoAutomatico(Accession $accession): void
    {        
        if ($this->verificarSeDadosConferemTSE($accession)) {
            Log::info('CPF do prefeito conferem com o TSE', ['accession' => $accession]);            
            $this->makeFluxoAutomatico($accession);
        }
    }

    /**
     * @param Accession $accession
     */
    private function makeFluxoAutomatico(Accession $accession)
    {        
        $this->confirm($accession->id, automaticamente: true);
    }

    /**
     * @param Accession $accession
     * @return bool
     */
    private function verificarSeDadosConferemTSE(Accession $accession)
    {
        $prefeito = $this->userService->find($accession->prefeito_id);
        $uf = $accession->county()->first()->state()->first()->sigla;
        $municipio = Str::upper($accession->county()->first()->name);
        return $this->getCPFExistsInTSE($prefeito->cpf, $uf, $municipio);
    }

    /**
     * @param string $cpf
     * @param string $uf
     * @return bool
     */
    private function getCPFExistsInTSE(string $cpf, string $uf, string $municipio): bool
    {
        return DB::table('candidatos AS c')
            ->distinct()
            ->select('c.*')
            ->where('c.NR_CPF_CANDIDATO', '=', $cpf)
            ->where('c.SG_UF', '=', $uf)
            ->where('c.NM_UE', '=', $municipio)
            ->where('c.CD_CARGO', '=', 11)
            ->whereIn('c.CD_SIT_TOT_TURNO', [1, 2])
            ->exists();
    }

    /**
     * @param string $cpf
     * @throws \Exception
     */
    private function validateIfCPFExists(string $cpf)
    {
        if ($this->userService->getRepository()->existByCPF($cpf)) {
            throw new \Exception('CPF já existe na base');
        }
    }

    /**
     * @param string $email
     * @throws \Exception
     */
    private function validateIfEmailExists(string $email)
    {
        if ($this->userService->getRepository()->existByEmail($email)) {
            throw new \Exception('E-mail já existe na base');
        }
    }

    /**
     * @param $id
     * @param null $params
     * @param false $automaticamente
     */
    public function confirm($id, $params = null, $automaticamente = false)
    {        
        $accession = $this->find($id);
        $accession->status = $automaticamente ?
            Accession::STATUS['APROVADO_AUTOMATICAMENTE'] :
            Accession::STATUS['APROVADO'];
        $accession->save();
        
        $accession = $this->find($accession->id);        

        $prefeito = $this->userService->find($accession->prefeito_id);
        $gestor = $this->userService->find($accession->gestor_politico_id);
        
        Mail::to($prefeito)->send(
            new ApprovePrefeito($accession)
        );

        Mail::to($gestor)->send(
            new ApproveGestor($accession)
        );
               
        $this->createDefaultData($prefeito, $gestor);   
        Log::info('confirmado adesão', ["prefeito" => $prefeito, "gestor" => $gestor, "accession" => $accession]);    
    }

    /**
     * @param $id
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public function reject($id, $params)
    {
        $accession = $this->find($id);
        $statusAccession = $accession->status;
        $accession->status = Accession::STATUS['REPROVADO'];
        $accession->rejection_description = $params['rejection_description'];
        $accession->save();

        $prefeito = $this->userService->find($accession->prefeito_id);
        $gestor = $this->userService->find($accession->gestor_politico_id);

        try {
            $this
                ->checkCanDeleteAccessionIfIsAlreadyApproved($statusAccession)
                ->sendEmails($accession, $prefeito, $gestor)
                ->excluirPrefeito($prefeito->id)
                ->excluirGestor($gestor->id)
                ->excluirAdesao($accession->id);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() ?? 'Não foi possível excluir a adesão');
        }
    }

    /**
     * @param string $statusAccession
     * @return $this
     * @throws \Exception
     */
    private function checkCanDeleteAccessionIfIsAlreadyApproved(string $statusAccession): self
    {
        if ($statusAccession === Accession::STATUS['APROVADO'] ||
            $statusAccession === Accession::STATUS['APROVADO_AUTOMATICAMENTE']
        ) {
            $user = $this->userService->find(Auth::user()->getAuthIdentifier());
            if ($user->occupation_id !== Occupation::GESTOR_NACIONAL) {
                throw new \Exception('Apenas gestores nacionais podem excluir uma adesão aprovada.');
            }
        }
        return $this;
    }

    private function sendEmails($accession, $prefeito, $gestor)
    {
        Mail::to($prefeito)->send(
            new RejectPrefeito($accession)
        );

        Mail::to($gestor)->send(
            new RejectGestor($accession)
        );

        return $this;
    }

    private function excluirPrefeito($prefeitoId)
    {
        $this->userService->delete($prefeitoId);
        return $this;
    }

    private function excluirGestor($gestorId)
    {
        $this->userService->delete($gestorId);
        return $this;
    }

    private function excluirAdesao($adesaoId)
    {
        $this->delete($adesaoId);
        return $this;
    }

    /**
     * @param $params
     * @param $prefeito
     * @param $gestor
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function saveAdesao($params, $prefeito, $gestor)
    {

        $adesao = Arr::get($params, 'form1');

        $countyService = app()->make(CountyService::class);

        $form['county_id'] = $countyService->find($adesao['county_id'])->id;
        $form['prefeito_id'] = $prefeito->id;
        $form['gestor_politico_id'] = $gestor->id;
        $form['status'] = Accession::STATUS['PENDENTE'];
        return $this->getRepository()->save($form);
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function savePrefeito($params)
    {
        $params['form2']['county_id'] = $params['form1']['county_id'];
        $params['form2']['profile_id'] = Profile::inRandomOrder()->first()->id;
        return $this->userService->save(new Request($params['form2']));
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function saveGestorDaPolitica($params)
    {
        $params['form3']['county_id'] = $params['form1']['county_id'];
        $params['form3']['profile_id'] = Profile::inRandomOrder()->first()->id;
        return $this->userService->save(new Request($params['form3']));
    }

    /**
     * Confirma o form externo
     * @param $accessionId
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public function confirmExternal($accessionId, $userId, $params): bool
    {
        $accession = $this->find($accessionId);

        $user = $this->userService->find($userId);
        
        if (!$accession) {
            throw new \Exception('Adesão não encontrada.');
        }

        $prefeito = $this->getRepository()
            ->where(['id' => $accession->id, 'prefeito_id' => $user->id])->first();

        $gestor = $this->getRepository()
            ->where(['id' => $accession->id, 'gestor_politico_id' => $user->id])->first();

        if (!$prefeito && !$gestor) {
            throw new \Exception('Usuário não pertence a essa adesão');
        }

        if ($gestor) {
            $accession->status_gestor_politico = Accession::STATUS['CONFIRMADO'];
        }

        if ($prefeito) {
            $accession->status_prefeito = Accession::STATUS['CONFIRMADO'];
        }
        
        // fluxo automático.
        if ($accession->status === Accession::STATUS['PENDENTE']) {
            $this->checkFluxoAutomatico($accession);
        }
        
        Log::info('prefeito confirmado adesão', ["prefeito" => $prefeito, "gestor" => $gestor, "accession" => $accession]);
        return $accession->save();
    }

    /**
     * @param $accessionId
     * @param $userId
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public function confirmExternalPassword($accessionId, $userId, $params): bool
    {
        $accession = $this->find($accessionId);

        if (!$accession) {
            throw new \Exception('Adesão não encontrada.');
        }

        $user = $this->userService->getRepository()->find($userId);

        $gestor = $this->getRepository()
            ->where(['id' => $accession->id, 'gestor_politico_id' => $user->id])->first();

        if (!$gestor) {
            throw new \Exception('Usuário não pertence a essa adesão');
        }

        $accession->status_gestor_politico = Accession::STATUS['CONFIRMADO'];

        $user->password = Hash::make($params['password']);
        $user->save();
        
        return $accession->save();
    }

    /**
     * Valida form externo para usuario continuar
     * @param $accessionId
     * @param $userId
     * @throws \Exception
     */
    public function validateAccessionUser($accessionId, $userId)
    {
        $this->validateIfUserExists($accessionId, $userId)
            ->validateIfAccessionExists($accessionId, $userId)
            ->validateIfUserBelongsToAccession($accessionId, $userId)
            ->validateUserAlreadyConfirmed($accessionId, $userId);
    }


    /**
     * Valida set password externo para usuario logar no sistema (gestor)
     * @param $accessionId
     * @param $userId
     * @throws \Exception
     */
    public function validateAccessionUserPassword($accessionId, $userId)
    {
        $this->validateIfUserExists($accessionId, $userId)
            ->validateIfAccessionExists($accessionId, $userId)
            ->validateIfUserBelongsToAccession($accessionId, $userId)
            ->validateUserAlreadyCreatedPassword($accessionId, $userId);
    }

    /**
     * @param $accessionId
     * @param $userId
     * @return $this
     * @throws \Exception
     */
    private function validateIfUserExists($accessionId, $userId)
    {
        $user = $this->userService->find($userId);
        if (!$user) {
            throw new \Exception('Usuário não existe');
        }
        return $this;
    }

    /**
     * @param $accessionId
     * @param $userId
     * @return $this
     * @throws \Exception
     */
    private function validateIfAccessionExists($accessionId, $userId)
    {
        $accession = $this->find($accessionId);
        if (!$accession) {
            throw new \Exception('Adesão não existe');
        }
        return $this;
    }

    /**
     * @param $accessionId
     * @param $userId
     * @return $this
     * @throws \Exception
     */
    private function validateIfUserBelongsToAccession($accessionId, $userId)
    {

        $accession = $this->find($accessionId);
        $user = $this->userService->find($userId);

        $existsPrefeito = $this->getRepository()
            ->where(['id' => $accession->id, 'prefeito_id' => $user->id])->first();
        $existsGestor = $this->getRepository()
            ->where(['id' => $accession->id, 'gestor_politico_id' => $user->id])->first();

        if (!$existsPrefeito && !$existsGestor) {
            throw new \Exception('Usuário não pertence a essa adesão');
        }

        return $this;
    }

    /**
     * @param $accessionId
     * @param $userId
     * @return $this
     * @throws \Exception
     */
    private function validateUserAlreadyConfirmed($accessionId, $userId)
    {

        $accession = $this->find($accessionId);
        $user = $this->userService->find($userId);

        $existsPrefeito = $this->getRepository()
            ->where(['id' => $accession->id, 'prefeito_id' => $user->id])->first();
        $existsGestor = $this->getRepository()
            ->where(['id' => $accession->id, 'gestor_politico_id' => $user->id])->first();

        if ($existsPrefeito) {
            $confirmedPrefeito = $this->getRepository()
                ->where([
                    'id' => $accession->id,
                    'prefeito_id' => $user->id,
                    'status_prefeito' => 'confirmado'
                ])
                ->first();
            if ($confirmedPrefeito) {
                throw new \Exception('Usuário já confirmou a adesão');
            }
        }

        if ($existsGestor) {
            $confirmedPrefeito = $this->getRepository()
                ->where([
                    'id' => $accession->id,
                    'prefeito_id' => $user->id,
                    'status_gestor_politico' => 'confirmado'
                ])
                ->first();
            if ($confirmedPrefeito) {
                throw new \Exception('Usuário já confirmou a adesão');
            }
        }

        return $this;
    }

    /**
     * @param $accessionId
     * @param $userId
     * @return $this
     * @throws \Exception
     */
    private function validateUserAlreadyCreatedPassword($accessionId, $userId)
    {
        $user = $this->userService->find($userId);
        if (!empty($user->password)) {
            throw new \Exception('Usuário já cadastrou a senha');
        }
        return $this;
    }

    /**
     * @param Accession $accession
     * @return string
     */
    public function getPendencies(Accession $accession)
    {
        $this->registrarValidacoes();

        $resultadoValidacoes = array_map(
            fn(Closure $validacao) => call_user_func($validacao, $accession),
            $this->validacoes
        );

        $validacaoComErro = array_filter($resultadoValidacoes, function ($i) {
            return $i !== false;
        });

        return implode(', ', array_values($validacaoComErro));
    }

    /**
     * @inheritdoc
     */
    public function registrarValidacoes(): void
    {
        $this->registrarValidacao($this->temGestorPoliticoPendente());
        $this->registrarValidacao($this->temPrefeitoPendente());
    }


    /**
     * @param Closure $validacao
     * @return $this
     */
    protected function registrarValidacao(Closure $validacao)
    {
        $this->validacoes[] = $validacao;

        return $this;
    }

    /**
     * @return Closure
     */
    private function temGestorPoliticoPendente(): Closure
    {
        return function (Accession $accession) {
            $msg = 'Gestor político com status pendente';
            return $accession->status_gestor_politico === 'pendente' ? $msg : false;
        };
    }

    /**
     * @return Closure
     */
    private function temPrefeitoPendente(): Closure
    {
        return function (Accession $accession) {
            $msg = 'Prefeito com status pendente';
            return $accession->status_prefeito === 'pendente' ? $msg : false;
        };
    }

    /**
     * @return mixed
     */
    public function preRequisite($id = null)
    {
        $stateService = app()->make(StateService::class);
        $arr['states'] = generateSelectOption($stateService->getRepository()->list());
        return $arr;
    }

    /**
     * createDefaultGovernmentOffices function
     *
     * @param User $prefeito
     * @param User $gestor
     * @return void
     */
    private function createDefaultGovernmentOffices(User $prefeito, User $gestor): void
    {
        $typeUsers = [$gestor];

        foreach ($typeUsers as $typeUser) {
            foreach (GovernmentOffice::DEFAULT as $default) {
                $governmentOffice = new GovernmentOffice();
                $governmentOffice->name = $default['name'];
                $governmentOffice->email = $default['email'];
                $governmentOffice->county_id = $typeUser->county_id;
                $governmentOffice->type = $default['type'];
                $governmentOffice->uuid = Uuid::uuid4();
                $governmentOffice->save();
            }
        }
    }

    /**
     * createDefaultGovOfficeReasonDelayVaccine function
     *
     * @return void
     */
    private function createDefaultGovOfficeReasonDelayVaccine(int $countyId): void
    {
        $reasonsDelayVaccine = ReasonDelayVaccine::where('is_forwarding', true)->get();
        $governmentOffices = GovernmentOffice::where('type', '1')->where('county_id', $countyId)
            ->orWhere('type', '2')->where('county_id', $countyId)
            ->get();
        foreach ($reasonsDelayVaccine as $reasonDelayVaccine) {
            $reasonDelayVaccine->pivot = new \App\Models\ReasonDelayVaccineGovernmentOffice();
            foreach ($governmentOffices as $governmentOffice) {
                $reasonDelayVaccine->pivot->insert([
                    'government_office_id' => $governmentOffice->id,
                    'reason_delay_vaccine_id' => $reasonDelayVaccine->id
                ]);                
            }
        }
    }

    /**
     * createDefaultGroup function
     *
     * @param integer $countyId
     * @return void
     */
    private function createDefaultGroupSubGroup(int $countyId): void
    {
        $params['name'] = 'Região';
        $params['county_id'] = $countyId;
        $group = $this->groupService->getRepository()->save($params);

        $subGroupService = app()->make(SubGroupService::class);
        $params['name'] = 'Sub Região';
        $params['group_id'] = $group->id;
        $subGroup = $subGroupService->getRepository()->save($params);

        event(new SubGroupCreatedEvent($subGroup));        
    }

    /**
     * createDefaultDeadline function
     *
     * @param integer $countyId
     * @return void
     */
    private function createDefaultDeadline(int $countyId): void
    {        
        $deadLineSeeder = app()->make(DeadlineSeeder::class);
        $deadLineSeeder->run($countyId);        
    }

    /**
     * createDefaultData function
     *
     * @param User $prefeito
     * @param User $gestor
     * @return void
     */
    private function createDefaultData(User $prefeito, User $gestor)
    {
        $this->createDefaultGovernmentOffices($prefeito, $gestor);        
        $this->createDefaultGovOfficeReasonDelayVaccine($gestor->county_id);        
        $this->createDefaultGroupSubGroup($gestor->county_id); 
        $this->createDefaultDeadline($gestor->county_id);   
    }

    public function sendErrata(): void
    {
        $adesoesPendentes = Accession::where('status_prefeito', 'pendente')
            //->where('status_gestor_politico', 'pendente')
            ->where('status', 'aprovado')
            ->whereYear('updated_at', '=', '2023')
            ->whereMonth('updated_at', '=', '02')
            ->whereDay('updated_at', '17')
            ->get();

        foreach ($adesoesPendentes as $adesao) {

            $accession = $this->find($adesao->id);

            $prefeito = $this->userService->find($accession->prefeito_id);
            $gestor = $this->userService->find($accession->gestor_politico_id);

            $accession->status = Accession::STATUS['PENDENTE'];
            $accession->save();

            Mail::to($prefeito)->send(
                new ErrataPrefeito($accession)
            );

            Mail::to($gestor)->send(
                new ErrataGestor($accession)
            );
        }
    }

    /**
     * @param $userId
     * @throws \Exception
     */
    public function resendMailAccession($userId)
    {
        $user = $this->userService->find($userId);

        $prefeitoAccession = $this->getRepository()
            ->where(['prefeito_id' => $user->id])->first();

        $gestorAccession = $this->getRepository()
            ->where(['gestor_politico_id' => $user->id])->first();

        if (!$prefeitoAccession && !$gestorAccession) {
            throw new \Exception('Usuário não encontrado em nenhuma adesão.');
        }

        if ($prefeitoAccession) {
            Mail::to($user)->send(
                new RegisterPrefeito($prefeitoAccession)
            );
        }

        if ($gestorAccession) {
            Mail::to($user)->send(
                new ApproveGestor($gestorAccession)
            );
        }
    }

    public function selo(array $params, string $siglaMunicipio)
    {
        $sigla = Str::upper($siglaMunicipio);
        $states = $this->stateService->getRepository()->all($params)->toArray();
        if(array_search($sigla, array_column($states, 'sigla')) === false) {
            throw new \Exception('Estado não encontrado.');
        }
        return $this->repository->selo($params, $sigla);
    }
}
