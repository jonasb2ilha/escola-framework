<?php

namespace App\Controllers\Auth;

use App\Controllers\Validate\Validate;
use App\Models\Recovery;
use App\Models\User;
use Core\BaseController;

class ResetPasswordController extends BaseController {


	/**
	 * vericar os dados digitados
	 * recupera o token 
	 * valida os dados
	 * criptografia a nova senha
	 * deleta o token criado para o uodate da senha
	 * redireciona para page de login
	 */
	public function resetPassword() {

		$data = $this->post();

		$recoveryToken = Recovery::where('token', $data->token)->first();

		if (!$recoveryToken) {
			flash('message', 'Oops! Código inválido ou expirado!<br> Por favor, solicite um novo link de redefinição.');
			return redirect('/recuperar');
		}

		$getErrors = Validate::newPassword($data);

		if ($getErrors) {
			return Redirect::route('/recovers/account/token/'. $data->token, [
				'errors' => $getErrors
			]);
		}

		$password = HashPassword($data->password);

		if (User::find($data->userId)->update(['password' => $password])) {
			Recovery::find($recoveryToken->id)->delete();
			flash('message', 'Senha atualizada com sucesso!', 'success');
			return redirect('/login');
		}

		flash('message', 'Error inesperado, tente novamente ou contate um administrador', 'warning');
		return back();


	}

}