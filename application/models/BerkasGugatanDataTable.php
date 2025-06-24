<?php

use App\Libraries\RequestBody;

defined('BASEPATH') or exit('No direct script access allowed');

class BerkasGugatanDataTable extends CI_Model
{
  var $table = 'berkas_gugatan';
  var $column_order = ['nomor_perkara', 'tanggal_pendaftaran', 'tanggal_putusan', 'majelis_hakim', 'keterangan'];
  var $column_search = ['nomor_perkara', 'tanggal_pendaftaran', 'tanggal_putusan', 'majelis_hakim', 'keterangan'];
  var $order = ['id' => 'desc'];

  private function _get_datatables_query()
  {
    $this->load->database();
    $this->db->select(
      [
        "*",
        "datediff(curdate(), berkas_gugatan.tanggal_terima) as selisih",
        "(SELECT posisi_ekspedisi.posisi FROM berkas_ekspedisi JOIN posisi_ekspedisi ON berkas_ekspedisi.save_point = posisi_ekspedisi.id WHERE berkas_ekspedisi.berkas_id = berkas_gugatan.id ORDER BY save_point DESC LIMIT 1) as ekspedisi"
      ],
      false
    );
    $this->db->from($this->table);
    if (isset($_GET["filter"])) {
      if ($_GET["type"] == "range") {
        $start = $_GET["start"];
        $end = $_GET["end"];
        $this->db->where("created_at >= '$start' AND created_at <= '$end'");
      }

      if ($_GET["type"] == "year") {
        $year = RequestBody::get()->year;
        $this->db->where("YEAR(created_at) = '$year'");
      }
    }

    $i = 0;
    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->column_search) - 1 == $i) $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by(
        $this->column_order[$_POST['order']['0']['column']],
        $_POST['order']['0']['dir']
      );
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables()
  {
    $this->_get_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    return $this->db->get()->result();
  }

  public function count_filtered()
  {
    $this->_get_datatables_query();
    return $this->db->count_all_results();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }
}
