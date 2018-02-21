<?php

namespace App\Controllers\Site;

use Core\BaseController;

class HomeController extends BaseController {

	/**
	 * @return view home site
	 */
	public function index(){
		return $this->view('site/home', true, [
			'title'	=> 'Home'
		]);
	}

}