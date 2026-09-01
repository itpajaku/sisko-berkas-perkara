<?php

use App\Libraries\MethodFilter;
use App\Models\Perkara;

class AutocompletePerkara extends APP_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    MethodFilter::must("get");
    $jenis = $this->input->get("jenis");
    
    $query = Perkara::select("nomor_perkara", "perkara_id")
      ->where("nomor_perkara", "like", $this->input->get("query") . "%");

    if ($jenis === 'gugatan') {
      $query->where("nomor_perkara", "not like", "%Pdt.P%");
    } else if ($jenis === 'permohonan') {
      $query->where("nomor_perkara", "like", "%Pdt.P%");
    }

    $perkara = $query->limit(10)
      ->orderBy("perkara_id", "desc")
      ->get()
      ->toArray();

    $this->output->set_content_type("application/json")
      ->set_output(json_encode($perkara));
  }
}
