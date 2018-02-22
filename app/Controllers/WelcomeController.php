<?php

namespace App\Controllers;

use Core\BaseController;

class WelcomeController extends BaseController {

	public function index(){
		return $this->view('welcome', true, [
			'title'	=> 'Bem vindo'
		]);
	}

}