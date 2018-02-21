<?php

namespace App\Controllers\Auth;

use App\Controllers\Validate\Validate;
use App\Models\User;
use Core\BaseController;
use Core\Password;
use Core\Redirect;

class RegisterController extends BaseController {

	/**
	 * @return view register
	 */
	public function index(){
		return $this->view('login/registre', true, [
			'title'	=> 'My frame'
		]);
	}

	/**
	 * recuperar os dados 
	 * valdia os dados
	 * e registrar um novo usuário no sistema e realiza o login automaticamente
	 * 
	 * @return true or false
	 */
	public function store() {

		$data = $this->post();
		$getErrors = Validate::registreUser($data);
		
		if ($getErrors) 
			return Redirect::route('/registre', [
				'errors'	=> $getErrors
			]);

		$dados = [
			'name'		=> $data->name,
            'user'      => $data->user,
	   		'email'		=> $data->email,
	   		'password'	=> Password::hash($data->password),
	   		'role'		=> 1,
	   		'status'	=> 1
		];

		if (User::create($dados)) {

			Auth($data, true);

		}
				
	}

}