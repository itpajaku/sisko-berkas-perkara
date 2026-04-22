<?php

class MY_Exceptions extends CI_Exceptions
{
	// overide the default show_404 method
	public function show_404($page = "", $log_error = true)
	{
		set_status_header(200);
		echo "Page not found";
	}

	public function show_error(
		$heading,
		$message,
		$template = "error_general",
		$status_code = 500,
	) {
		if ($status_code == 403) {
			$message .= " You do not have permission to access this resource.";
		}
		$triggerEvent = [
			"auth-event" => [
				"status" => "login-failed",
				"message" => "Login gagal, silahkan periksa kembali",
			],
		];
		header("HX-Trigger: " . json_encode($triggerEvent));
		set_status_header(200);
		echo "An error occurred. " . $message;
		die;
	}
}
