<?php

use App\Libraries\AuthData;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\Avatar;

defined('BASEPATH') or exit('No direct script access allowed');

class ProfileController extends APP_Controller
{
  public function edit_page()
  {
    $avatar = Avatar::where('user_id', AuthData::getUserData()->userid)->first();
    Templ::render("profile/edit_profile_page", [
      "title" => "Edit Profil",
      "avatar" => $avatar,
    ])
      ->layout("layouts/main_layout");
  }

  public function update_avatar()
  {
    try {
      $userId = AuthData::getUserData()->userid;
      $avatar = Avatar::where('user_id', $userId)->first();

      if (!$avatar) {
        $avatar = new Avatar();
        $avatar->user_id = $userId;
      }

      $message = "Berhasil memperbarui avatar. Butuh relogin untuk melihat perubahan.";
      $avatar->dice_bear_query = RequestBody::post("avatar");
      $avatar->save();

      $this->session->set_flashdata("success_alert", $message);
      $this->output
        ->set_header('HX-Refresh: true')
        ->set_header('HX-Trigger: ' . json_encode([
          "htmx:toastr" => [
            "type" => "success",
            "message" => $message
          ]
        ]))
        ->set_output($message);
    } catch (\Throwable $th) {
      $this->output
        ->set_header('HX-Trigger: ' . json_encode([
          "htmx:toastr" => [
            "type" => "error",
            "message" => "Gagal memperbarui avatar: " . $th->getMessage()
          ]
        ]))->set_output($th->getMessage());
    }
  }
}
