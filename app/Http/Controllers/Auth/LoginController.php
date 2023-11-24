<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\AbstractController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends AbstractController
{
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var LoginService
	 */
	protected $service;

	/**
	 * LoginController constructor.
	 * @param Request $request
	 * @param LoginService $service
	 */
	public function __construct(Request $request, LoginService $service)
	{
		$this->request = $request;
		$this->service = $service;
	}

    /**
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\JsonResponse|void
     */
	public function login(LoginRequest $loginRequest)
	{
		try {
			if ($loginRequest->validated()) {
				return $this->ok($this->service->login($this->request->all()));
			}
		} catch (ValidationException $validationException) {
			return $this->error(
				'Dados inválidos',
				$validationException->errors(),
				$validationException->status
			);
		}
	}
}
