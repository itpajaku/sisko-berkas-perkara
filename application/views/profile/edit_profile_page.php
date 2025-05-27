<div class="container-fluid">
  <?= App\Libraries\Templ::component("layouts/page_header", [
    "breadcrumbs" => [
      ["name" => "Dashboard", "url" => "javascript:void(0)"],
      ["name" => "Edit Profile", "url" => "javascript:void(0)"],
      ["name" =>  App\Libraries\AuthData::getUserData()->fullname, "url" => "javascript:void(0)"],
    ],
    "page_name" => "Edit Profil",
  ]); ?>
  <div class="row">
    <?= $this->session->flashdata("error_alert") ?>
    <?= $this->session->flashdata("success_alert") ?>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 border position-relative overflow-hidden">
        <div class="card-body p-4">
          <h4 class="card-title">Change Profile</h4>
          <p class="card-subtitle mb-4">Pilih avatar anda</p>
          <div class="text-center">
            <img id="preview-img" src="https://api.dicebear.com/9.x/micah/svg?seed=<?= $avatar ? $avatar->dice_bear_query : 'w0e9as' ?>" alt="modernize-img" class="img-fluid rounded-circle" width="120" height="120">
            <div class="d-flex align-items-center justify-content-center my-4 gap-6">
              <button id="btn-prev" class="btn btn-warning">
                <i class="ti ti-chevrons-left"></i>
                Prev
              </button>
              <button id="btn-next" class="btn btn-primary">
                <i class="ti ti-chevrons-right"></i>
                Next
              </button>
              <form
                hx-patch="<?= base_url("profile/update_avatar") ?>"
                hx-swap="none"
                class="d-flex align-items-center gap-2">
                <input type="text" name="avatar" id="input-avatar" value="<?= $avatar ? $avatar->dice_bear_query : 'w0e9as' ?>" hidden>
                <button class="btn btn-success">
                  <i class="ti ti-device-floppy"></i>
                  Simpan
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 border position-relative overflow-hidden">
        <div class="card-body p-4">
          <h4 class="card-title">Change Password</h4>
          <p class="card-subtitle mb-4">To change your password please confirm here</p>
          <div class="d-flex flex-column align-items-center justify-content-center h-100">
            <div class="alert bg-warning-subtle">
              <p>Maaf, untuk sementara ini reset password hanya bisa dilakukan di SIPP</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var avatarList = [];
  window.addEventListener("load", function() {
    $("#btn-next").on("click", function() {
      avatar = make_randomw(5);
      avatarList.push(avatar);
      $("#input-avatar").val(avatar);
      $("#preview-img").attr("src", `https://api.dicebear.com/9.x/micah/svg?seed=${avatar}`);
    });

    $("#btn-prev").on("click", function() {
      if (avatarList.length === 0) {
        return false;
      }

      avatarList.pop();
      avatar = avatarList[avatarList.length - 1];
      $("#input-avatar").val(avatar);
      $("#preview-img").attr("src", `https://api.dicebear.com/9.x/micah/svg?seed=${avatar}`);
    });

  });

  function make_randomw(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }
</script>