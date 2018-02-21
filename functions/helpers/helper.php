<?php 

use App\Controllers\Auth\AuthController;
use Core\BaseController;
use Core\Password;
use Core\Redirect;
use Core\Request;
use Core\Session;




if (! function_exists('baseUrl')) {

	function baseUrl() {
		$conf = load_config('app');
		return $conf['urlBase'];
	}

}


if (! function_exists('load_config')) {

	function load_config($config) {

		if (file_exists(__DIR__ . "/../../config/{$config}.php")) {
			return require __DIR__ . "/../../config/{$config}.php";
			return 'ok';
		} else {
			return false;
		}
	}

}



if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd(...$args)
    {
        foreach ($args as $x) {
            (new Dumper)->dump($x);
        }

        die(1);
    }
}


function token() {
	return hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
}

function authUser() {
	return session('user');
}

function Auth($data, $create = null){
	$login = new AuthController;
	$nivel = $login->login($data);


	if (!$create) {
		if ($nivel == 'admin') {
			flash('message', 'Bem vindo, '.session('user')['user'], 'info');
			return Redirect::route('/admin/home');
		}
	}
	
	if ($create) {

		flash('message', 'Você foi registrado com sucesso', 'success');
	 	return Redirect::route('/');

	}
	
	if ($nivel == 'user') {
		flash('message', 'Bem vindo, '.session('user')['user'], 'info');
		return Redirect::route('/');
	}

}


function assets($name) {
	echo "http://$_SERVER[HTTP_HOST]/assets/{$name}";
}

function HashPassword($pass) {
	return Password::hash($pass);
}

function Verify($password, $hash) {
	return Password::verify($password, $hash);
}

// function View($view, $layout = null, array $data= null) {
// 	$viewF = new BaseController;
// 	return $viewF->view($view, $layout, $data);
// }

function redirect($url) {
	return Redirect::route($url);
}

function back() {
	return Redirect::back();
}

function logout(){
	unset($_SESSION['logged']);
}


function flash($key, $message, $type = 'danger') {

	if (!isset($_SESSION['flash'][$key])) 
		$_SESSION['flash'][$key] = "
			 <div class='alert alert-".$type." alert-dismissible fade show' role='alert'>
		        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		            <span aria-hidden='true'>&times;</span>
		        </button>
		        <strong>".$message."</strong> 
		    </div>";
}

function get($key) {

	if(isset($_SESSION['flash'][$key])) {
		$message = $_SESSION['flash'][$key];

		unset($_SESSION['flash'][$key]);

		return $message ?? '';
	}

}


function getError($key) {

	if(isset($_SESSION[$key])) {
		$message = $_SESSION[$key];

		return $message ?? '';
		Session::destroy($key);
	}

}

function session($param) {
	if (isset($_SESSION[$param])) {
		return $_SESSION[$param];
	} else {
		return false;
	}
	
}