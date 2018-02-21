<?php

namespace App\Controllers\Auth;

use App\Controllers\Validate\Validate;
use App\Models\Recovery;
use App\Models\User;
use Core\BaseController;
use Core\Mail;
use Core\Redirect;
use Core\Request;

class ForgotPasswordController extends BaseController {


	/**
	 * @return view login
	 */
	public function index(){
		return $this->view('login/recuperar', true, [
			'title'		=> 'Recuperar conta'
		]);
	}


	/**
	 * Verifica se o email é válido para trocar da senha 
	 * Se existir, registra no BD uma requisição para trocar a senha e envia um email com o link da page para troca de senha
	 * 
	 * @return true
	 */
	public function recoversAccount() {
		$data = $this->post();
		$getErrors = Validate::email($data);

		if ($getErrors) {

			return Redirect::route('/recuperar', [
                'errors' => $getErrors
            ]);
		}

		$dataUser = User::where('email', $data->email)->first();

		if ($dataUser) {
			$email = new Mail;

			$token = token();

			if ($email->enviaEmail($dataUser->email, $dataUser->name, $token, $dataUser->id)) {

				if (Recovery::where('user_id', $dataUser->id)->first()) {
					Recovery::where('user_id', '=', $dataUser->id)->delete();
				}

				Recovery::create(['user_id' => $dataUser->id, 'token' => $token]);			    

				flash('message', 'Um email com um link de redefinição de senha foi enviada para voce!', 'warning');
				return redirect('/recuperar');

			} else {

				flash('message', 'Error inesperado tente novamente ou contate um administrador!');
				return redirect('/recuperar');
			}
			
		} else {

			flash('message', 'Este <b>E-mail</b> não está cadastrado em nosso sistema!', 'warning');
			return redirect('/recuperar');

		}

	}

	/**
	 * Page para troca de senha e verifica se o token existe e está ativo
	 * 
	 * @param  int $id de usuário
	 * @param  string $token para troca de senha
	 * @return view new password
	 */
	public function recoversNewPasswordPage($id, $token) {

		$token = Recovery::where('token', $token)->first();
		if (!$token) {
			flash('message', 'Oops! Código inválido ou expirado!<br> Por favor, solicite um novo link de redefinição.');
			return redirect('/recuperar');
		}

		return $this->view('login/newPasswordAcc', true, [
			'title'		=> 'Definir nova senha',
			'token'		=> $token->token,
			'userId'	=> $id
		]);
	}

}