<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
  public function __construct()
  {
    parent::__construct();
  }
  public function service($service = '', $params = NULL, $object_name = NULL)
  {
    if (empty($service)) {
      return $this;
    }
    if (isset($this->_ci_services[$service])) {
      return $this;
    }

    $service_name = ucfirst($service);
    $service_path = APPPATH . 'services/' . $service_name . '.php';

    if (!file_exists($service_path)) {
      show_error("Service file not found: {$service_path}");
    }

    include_once($service_path);

    if (empty($object_name)) {
      $object_name = strtolower($service_name);
    }

    if (!class_exists($service_name, false)) {
      show_error("Unable to find the service class: {$service_name}");
    }

    $CI = &get_instance();
    $CI->$object_name = new $service_name($params);

    $this->_ci_services[$service] = $object_name;

    return $this;
  }
  protected $_ci_services = array();
}
