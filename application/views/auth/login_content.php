<a href="javascript:void(0)" class="text-nowrap logo-img text-center d-block py-3 w-100">
  <img src="<?= base_url($_SERVER['LOGO']) ?>" width="180" alt="">
</a>
<p class="text-center">Masuk Sebelum Melanjutkan</p>
<div id="login-alert"></div>
<form
  hx-post="<?= base_url("/login") ?>"
  hx-target="#login-alert"
  hx-on::before-request="$('#button_login').attr('disabled', true).html('Mohon Tunggu ...')"
  hx-on::after-request="$('#button_login').attr('disabled', false).html('Sign In')"
  autocomplete="off">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Username</label>
    <input type="text" name="identifier" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-4">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
  </div>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div class="form-check">
      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
      <label class="form-check-label text-dark" for="flexCheckChecked">
        Remeber this Device
      </label>
    </div>
    <!-- <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a> -->
  </div>
  <button id="button_login" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
  <div class="d-flex align-items-center justify-content-center">
    <p class="fs-3 mb-0 fw-bold">Copyrights to </p>
    <a class="text-primary fw-bold ms-2" href="javascript:void(0)"><?= $this->sysconf->NamaPN ?></a>
  </div>
</form>