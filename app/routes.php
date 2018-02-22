<?php 


$conf = load_config('app');


/*ROUTES DO SITE*/
$route[] = ['/', 			'WelcomeController@index'];


/* ROUTES LOGIN */

$route[] = ['/login', 						'Auth\LoginController@index'];
$route[] = ['/loginAuth', 					'Auth\LoginController@login'];
$route[] = ['/logout', 						'Auth\LoginController@logout'];

/* ROUTES RECUPERAR CONTA */
$route[] = ['/recuperar', 							'Auth\ForgotPasswordController@index'];
$route[] = ['/recovers/account', 					'Auth\ForgotPasswordController@recoversAccount'];
$route[] = ['/recovers/account/{id}/token/{id}', 	'Auth\ForgotPasswordController@recoversNewPasswordPage'];

$route[] = ['/recovers/account/exchange', 			'Auth\ResetPasswordController@resetPassword'];


/* CADASTRAR USER */
$route[] = ['/registre', 			'Auth\RegisterController@index'];
$route[] = ['/registre/store', 		'Auth\RegisterController@store'];


$route[] = ['/admin/home', 'Admin\AdminHomeController@index', ['middleware' => $conf['auth'] , 'role' => $conf['userAdmin']]];





return $route;