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

		$this->load = new BaseLoader();
	}
}

class APP_Controller extends CI_Controller
{
	/** @var Eloquent */
	public $eloquent;

	/** @var Sysconf */
	public $sysconf;

	/** @var MY_Loader */
	public $load; // <-- sudah tanpa type hint

	/** @var CI_Input */
	public $input;

	/** @var CI_Form_validation */
	public $form_validation;

	/** @var CI_Output */
	public $output;

	/** @var CI_Session */
	public $session;

	/** @var stdClass */
	public $userdata;

	public function __construct()
	{
		parent::__construct();

		$this->eloquent = new Eloquent();
		$this->eloquent->boot();

		$this->sysconf = new Sysconf($this->eloquent);

		\Carbon\Carbon::setLocale("id");

		AuthData::authenticatedPass();
		$this->userdata = AuthData::getUserData();
	}
}
