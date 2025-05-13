<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{

  public function service($service = '', $params = NULL, $object_name = NULL)
  {
    if (empty($service)) {
      return $this;
    }

    // Jika sudah diload, abaikan
    if (isset($this->_ci_services[$service])) {
      return $this;
    }

    // Nama file dan class
    $service_name = ucfirst($service);
    $service_path = APPPATH . 'services/' . $service_name . '.php';

    if (!file_exists($service_path)) {
      show_error("Service file not found: {$service_path}");
    }

    // Load file service
    include_once($service_path);

    // Tentukan nama instance di CI
    if (empty($object_name)) {
      $object_name = strtolower($service_name);
    }

    // Cek apakah class ada
    if (!class_exists($service_name, false)) {
      show_error("Unable to find the service class: {$service_name}");
    }

    // Inisialisasi service
    $CI = &get_instance();
    $CI->$object_name = new $service_name($params);

    // Tandai sebagai sudah diload
    $this->_ci_services[$service] = $object_name;

    return $this;
  }

  // Array penampung service yang sudah diload
  protected $_ci_services = array();
}
