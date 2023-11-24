<?php

namespace App\Services;

use App\Abstracts\AbstractService;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginService extends AbstractService
{
	protected $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	/**
	 * @param $params
	 * @return array
	 * @throws ValidationException
	 */
	public function login(array $params)
	{
		if (!$this->userService->getRepository()->existByEmail($params['email'])) {
			throw ValidationException::withMessages([
				'email' => ['E-mail não existe na base de dados.'],
			]);
		}

		$user = $this->userService->getRepository()->findByEmail($params['email']);

		if (!Hash::check($params['password'], $user->password)) {
			throw ValidationException::withMessages([
				'password' => ['Senha incorreta.'],
			]);
		}

		return [
			'token' => $user->createToken('bav')->plainTextToken,
			'user' => $user->only(['id', 'name', 'email', 'uuid'])
		];
	}
}
