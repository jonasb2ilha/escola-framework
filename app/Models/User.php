<?php

namespace App\Models;

use Core\BaseModelEloquent;

class User extends BaseModelEloquent
{
	/**
	 * [$table tabela users do bd]
	 * @var string
	 */
	public $table = 'users';

	/**
	 * 
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * [$fillable compos do bd]
	 * @var array
	 */
	protected $fillable = ['name', 'user', 'email', 'password', 'role', 'status'];

	
}