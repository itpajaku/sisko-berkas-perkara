<?php
defined("BASEPATH") or exit("No direct script access allowed");

use App\Traits\AuthRedirectDest;
use App\Libraries\Eloquent;
use App\Libraries\MethodFilter;
use App\Libraries\SippLogin;
use App\Libraries\Sysconf;
use App\Libraries\Templ;
use App\Services\AuthService;

class Auth extends CI_Controller
{
  public Templ $templ;
  public MY_Loader $load;
  public Eloquent $eloquent;
  public Sysconf $sysconf;
  public AuthService $authService;

  use AuthRedirectDest;

  public function __construct()
  {
    parent::__construct();

    $this->eloquent = new Eloquent();
    $this->eloquent->boot();


    $this->sysconf = new Sysconf($this->eloquent);
    $this->authService = new AuthService($this->eloquent);
  }

  public function index()
  {
    MethodFilter::must("get");

    Templ::render("auth/login_content")->layout("layouts/auth_layout");
  }

  public function login()
  {
    MethodFilter::mustHeader("Hx-Request");

    try {
      $user = $this->eloquent->capsule->connection("sipp")->table("sys_users")
        ->where("username", $this->input->post("identifier", true))
        ->first();

      if (!$user) {
        throw new Exception("Username tidak ditemukan");
      }

      $storedKey = SippLogin::validate(
        array($user->code_activation, $this->input->post("password", true)),
      );

      if ($user->password != $storedKey) {
        throw new Exception("Password tidak sesuai");
      }

      if ($user->block == 1) {
        throw new Exception("Akun anda tidak aktif");
      }

      $profile = $this->authService->getProfileData($user);

      if (!$profile) {
        throw new Exception("Akun anda tidak ditemukan");
      }

      if (!isset($this->redirectPage[$profile->groupid])) {
        throw new Exception("Akun anda tidak bisa digunakan");
      }

      $this->session->set_userdata("app_user_data", $profile);

      set_status_header(200);
      header("HX-Redirect: " . base_url($this->redirectPage[$profile->groupid]));
      echo $this->load->view("auth/auth_alert", ["message" => "Anda akan diarahakn sebentar lagi"], true);
    } catch (\Throwable $th) {
      echo $this->load->view("auth/auth_alert", ["message" => $th->getMessage()], true);
    }
  }

  public function logout()
  {
    MethodFilter::must("post");
    $this->session->unset_userdata("app_user_data");
    $this->session->sess_destroy();
    if (isset($this->input->request_headers()['Hx-Request'])) {
      $this->output->set_header("HX-Redirect: /auth")->set_output("Logout Berhasil");
      exit;
    }
    redirect("auth");
  }
}
