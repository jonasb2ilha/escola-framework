<?php

namespace App\Controllers\Admin;

use Core\BaseController;
use Core\Redirect;

class AdminHomeController extends BaseController
{
	/**
	 * Return view home admin
	 * 
	 * @var title page
	 */
	public function index() {  
        return $this->view('admin/home', true, [
        	'title'		=> 'Login', 
        ]);

	}
}		