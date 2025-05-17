<?php

use AzharLihan\CI3Modules\BaseLoader;
use App\Libraries\Eloquent;
use App\Libraries\Sysconf;
use App\Libraries\AuthData;
use Hashids\Hashids;

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load = new BaseLoader;
	}
}

class APP_Controller extends CI_Controller
{
	public Eloquent $eloquent;
	public Sysconf $sysconf;
	public Hashids $hash;
	public MY_Loader $load;
	public CI_Input $input;
	public CI_Form_validation $form_validation;
	public CI_Output $output;
	public CI_Session $session;
	public stdClass  $userdata;

	public function __construct()
	{
		parent::__construct();

		$this->eloquent = new Eloquent();
		$this->eloquent->boot();

		$this->sysconf = new Sysconf($this->eloquent);

		\Carbon\Carbon::setLocale("id");


		$this->hash = new Hashids();
		AuthData::authenticatedPass();
		$this->userdata = AuthData::getUserData();
	}
}
