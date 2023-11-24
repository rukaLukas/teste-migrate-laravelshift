<?php
namespace App\Service;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Abstracts\AbstractService;
use App\Exceptions\GeneralException;
use App\Infra\Repository\ProfileRepository;
use App\Validations\Profile\ProfileEnabledToSave;
use App\Interfaces\Repository\ProfileRepositoryInterface;

class ProfileService extends AbstractService
{
    /**
     * @var ProfileRepositoryInterface
     */
    protected $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @throws \Throwable
     */
    public function beforeSave(Request $request): void
    {
        $profile = new Profile($request->all());
        $profileEnabledToSave = new ProfileEnabledToSave();
        throw_if(
            !$profileEnabledToSave->validate($profile)->isValid(),
            new GeneralException($profileEnabledToSave->getErrors())
        );
    }
}
