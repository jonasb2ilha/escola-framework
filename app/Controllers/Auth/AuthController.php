<?php 

namespace App\Controllers\Auth;

use App\Models\LoginAttempt;
use App\Models\User;
use Core\Redirect;
use Core\Session;

class AuthController {

	/**
	 * @var mixed
	 */
	private $user;

	/**
	 *	Verifica o login do usuário e password
	 *	Verifica se o usuário está bloqueado ou não no sistema
	 *	Verifica tentativas de login erradas e registrar no bd
	 *	Verifica quantas tentativas ainda podem ser efetuadas e em caso de exceder o limit bloquear a conta para sua segurança
	 *	Se passar por todas verificações retorna se o usuário é USER ou ADMINISTRADOR
	 *
	 * 
	 * @param  Data array
	 * @return user/admin
	 */
	public function login($data) {
		

		/*	RECUPERA DADOS DO USUÁRIO */
		$this->user = User::where('user', $data->user)->first();


		/*	VERIFICA SE O USUÁRIO EXISTE */
		if ($this->user) {
			
			/*	VERIFICA SE O USUÁRIO ESTÁ ATIVO */
			if ($this->user->status == 1) {

				/*	VERIFICA SE A SENHA ESTÁ CORRETA*/
				if (Verify($data->password, $this->user->password)) {

					/*	DELETA TENTATIVAS ERRADAS */
					LoginAttempt::where('user_id', '=', $this->user->id)->delete();
						
					/*	LOGIN DE USUÁRIO COMUM */
					if ($this->user->role == 1)
						return $this->loggedInUser();

					/*	LOGIN DE USUÁRIO ADMINISTRADOR */
					if ($this->user->role == 3) 
						return $this->loggedInAdmin();

				} else {

					/*	REGISTRA UMA TENTATIVA DE LOGIN ERRADA */
					LoginAttempt::create(['user_id' => $this->user->id]);

					/*	CONTA QUANTAS TENTATIVAS AINDA RESTAM */
					$attempts = LoginAttempt::where(['user_id' => $this->user->id])->get()->count();

					/*	VERIFICA QUANTAS TENTATIVAS RESTAM */
					if ($attempts == 3 or $attempts == 4) {
						$total = 5 - $attempts;
						return $this->attempts($total);
					}

					/*	SE CHEGAR A 5 TENTATIVAS ERRADAS BLOQUEA O USUÁRIO */
					if ($attempts == 5) {
						User::where('id', $this->user->id)->update(['status' => 0]);
						return $this->isBlock();
					}

					/*	SENHA ERRADA */
					return $this->isNotLoggedIn();
				}

			} else {
				/*	USUPARIO BLOQUEADO */
				return $this->isNotActive();
			}

		} else {
			/*	USUÁRIO NÃO EXISTE */
			return $this->isNotLoggedIn();
		}
		
	}


	/**
	 * cria a session de usuário comum
	 * 
	 * @return user
	 */
	private function loggedInUser() {
		Session::sessionSet('logged', true);
		Session::sessionSet('user', $this->user);
		session_regenerate_id();
		return 'user';
	}

	/**
	 * cria a sesion de usuário administrador
	 * 
	 * @return admin
	 */
	private function loggedInAdmin() {
		Session::sessionSet('logged', true);
		Session::sessionSet('user', $this->user);
		session_regenerate_id();
		return 'admin';
	}

	/**
	 * retorna uma mensagem se o usuário estiver bloqueado e redireciona para page login
	 * 
	 * @return boolean
	 */
	private function isBlock() {
		flash('message', 'Error. Está conta foi bloqueada por excesso de tentativas.');
		return Redirect::route('/login');
	}

	/**
	 * retorna menssagem avisando o total de tentativas restantes e redireciona para page login
	 * 
	 * @param  total de tentativas
	 * @return boolean
	 */
	private function attempts($total) {
		flash('message', 'Suas tentativas estão terminando. Você só tem mais '. $total . ' tentativas!', 'warning');
		return Redirect::route('/login');
	}


	/**
	 * retorna mensagem avisando que o usuário está bloqueado e redireciona para page login
	 * 
	 * @return boolean
	 */
	private function isNotActive () {
		flash('message', 'Este usuário encontrase bloqueado em nosso sistema!');
		return Redirect::route('/login');
	}

	/**
	 * retorna mensagem avisando que o usuário não conseguiu efeturar o login e redireciona para page login
	 * 
	 * @return boolean
	 */
	private function isNotLoggedIn() {
		flash('message', 'Error, verifique se os dados estão corretos!');
		return Redirect::route('/login');
	}

}