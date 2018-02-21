<?php 

namespace App\Controllers\Auth;

use App\Controllers\Validate\Validate;
use App\Models\Recovery;
use App\Models\User;
use Core\BaseController;
use Core\Mail;
use Core\Redirect;
use Core\Request;
use Core\Session;

class LoginController extends BaseController
{

	/**
	 * @return view de login
	 */
	public function index()
	{
		
        return $this->view('login/login', true, [
        	'title'		=> 'Login', 
        ]);
	}

	/**
	 * recuepera dados do login
	 * valida os dados
	 * eo Auth faz a logica de login
	 */
	public function login() {
		
		$data = $this->post();
		$getErrors = Validate::login($data);

		if ($getErrors) {

			return Redirect::route('/login', [
                'errors' => $getErrors
            ]);
		}
		
		Auth($data, null);
		
	}

	/**
	 * @return desconectar usuário do sistema
	 */
	public function logout(){
		unset($_SESSION['logged'], $_SESSION['user']);
		flash('message', 'Usuário deslogado com sucesso!', 'dark');
        return redirect('/login');;
	}

}