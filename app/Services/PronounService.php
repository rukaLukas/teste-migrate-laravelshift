<?php
namespace App\Services;

use App\Abstracts\AbstractService;
use App\Infra\Repository\PronounRepository;
use App\Validations\User\UsersEnabledToSave;

class PronounService extends AbstractService
{
    /**
     * @var PronounRepository
     */
    protected $repository;

    public function __construct(PronounRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function preRequisite($id = null)
    {
        $arr['pronoun'] = generateSelectOption($this->targetPublicService->getRepository()->list());
        return $arr;
    }
}
