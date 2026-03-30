<?php

use App\Libraries\Hashid;
use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;
use App\Models\BerkasEkspedisi;
use App\Models\PosisiEkspedisi;
use Symfony\Component\Config\Builder\Method;
use Illuminate\Database\Capsule\Manager as DB;

class PengaturanEkspedisiController extends APP_Controller
{
  public function index()
  {
    $data = PosisiEkspedisi::select('*');

    $this->load->library('pagination');

    $config['base_url'] = base_url('pengaturan/ekspedisi/page');
    $config['total_rows'] = $data->get()->count();
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;

    /* Bootstrap 5 */
    $config['full_tag_open']   = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close']  = '</ul></nav>';
    $config['attributes']      = ['class' => 'page-link'];
    $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']   = '</span></li>';
    $config['num_tag_open']    = '<li class="page-item">';
    $config['num_tag_close']   = '</li>';

    $config['attributes'] = ['class' => 'page-link'];
    $offset = $this->uri->segment(4, 0);

    $this->pagination->initialize($config);
    Templ::render("pengaturan/ekspedisi_page", [
      'data' => $data->limit($config['per_page'])->offset($offset)->latest()->get(),
    ])
      ->layout('layouts/main_layout', [
        'title' => 'Pengaturan Ekspedisi Berkas',
      ]);
  }

  public function pagination($offset = 0)
  {
    if (!MethodFilter::isHeader("HX-Request")) {
      redirect("pengaturan/ekspedisi");
      exit;
    }
    MethodFilter::mustHeader("HX-Request");
    $this->load->library('pagination');
    $data = PosisiEkspedisi::select('*');

    $config['base_url'] = site_url('pengaturan/ekspedisi/page');
    $config['total_rows'] = $data->get()->count();
    $config['per_page'] = 10;
    $config['uri_segment'] = 4;
    $config['full_tag_open']   = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close']  = '</ul></nav>';
    $config['attributes']      = ['class' => 'page-link'];
    $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']   = '</span></li>';
    $config['num_tag_open']    = '<li class="page-item">';
    $config['num_tag_close']   = '</li>';
    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    $this->output->set_content_type('text/html')->set_output(
      Templ::component('pengaturan/components/tabel_ekspedisi', [
        'data' => $data->limit($config['per_page'])->offset($offset)->latest()->get(),
      ])
    );
  }

  public function form_tambah()
  {
    MethodFilter::mustHeader('HX-Request-Component');
    $this->output->set_content_type('text/html')->set_output(
      Templ::component('pengaturan/components/tambah_ekspedisi', [], true)
    );
  }

  public function tambah()
  {
    MethodFilter::must('POST');
    MethodFilter::mustHeader('HX-Request');
    try {
      $this->form_validation->set_rules(
        'posisi',
        'Posisi',
        'required|trim',
        [
          'required' => '%s wajib diisi.'
        ]
      );

      $this->form_validation->set_rules(
        'keterangan',
        'Keterangan',
        'required|trim',
        [
          'required' => '%s wajib diisi.'
        ]
      );

      $this->form_validation->set_rules(
        'status',
        'Status',
        'required',
        [
          'required' => '%s wajib dipilih.'
        ]
      );
      if ($this->form_validation->run() == false) {
        throw new Exception("Validasi Gagal");
      }

      PosisiEkspedisi::create([
        'posisi' => $this->input->post('posisi', true),
        'keterangan' => $this->input->post('keterangan', true),
        'status' => $this->input->post('status', true),
      ]);

      $htmxEvent = [
        'action:success' => true
      ];

      $this->output->set_content_type("text/html")
        ->set_header('HX-Trigger: ' . json_encode($htmxEvent))
        ->set_output(Templ::component('pengaturan/components/tambah_ekspedisi', [
          'alert' => Templ::component('components/success_alert', [
            'message' => 'Posisi Ekspedisi Berhasil Ditambahkan. Jendela akan ditutup dalam 2 detik.',
          ])
        ]));
    } catch (\Throwable $th) {
      $this->output->set_content_type("text/html")
        ->set_output(Templ::component('pengaturan/components/tambah_ekspedisi', [
          'alert' => Templ::component('components/exception_alert', [
            'message' => $th->getMessage(),
          ])
        ]));
    }
  }

  public function form_edit($id = null)
  {
    MethodFilter::mustHeader('HX-Request-Component');
    try {
      $data = PosisiEkspedisi::findOrFail(Hashid::singleDecode($id));
      $this->output->set_output(
        Templ::component("pengaturan/components/edit_ekspedisi", ['data' => $data])
      );
    } catch (\Throwable $th) {
      $this->output->set_content_type("text/html")
        ->set_output(Templ::component('components/exception_alert', [
          'message' => $th->getMessage(),
        ]));
    }
  }

  public function delete($id = null)
  {
    MethodFilter::must('DELETE');
    MethodFilter::mustHeader('HX-Request');
    try {
      $htmxEvent = [
        'action:success' => true
      ];
      $pos  = PosisiEkspedisi::findOrFail(Hashid::singleDecode($id));
      $checkBerkas = BerkasEkspedisi::where('save_point', $pos->id)->limit(1)->first();
      if ($checkBerkas) {
        throw new Exception("Ekspedisi ini masih digunakan pada riwayat ekspedisi berkas nomor " . $checkBerkas->berkas->nomor_perkara . ", anda hanya bisa menonaktifkan ekspedisi ini.", 1);
      }
      $pos->delete();
      $this->output->set_content_type("text/html")
        ->set_header('HX-Trigger: ' . json_encode($htmxEvent))
        ->set_output(Templ::component('components/success_alert', [
          'message' => "Hapus Berhasil. Jendela akan ditutup dalam 2 detik.",
        ]));
    } catch (\Throwable $th) {
      $this->output->set_content_type("text/html")
        ->set_output(Templ::component('components/exception_alert', [
          'message' => $th->getMessage(),
        ]));
    }
  }

  public function edit($id = null)
  {
    MethodFilter::must('PUT');
    MethodFilter::mustHeader('HX-Request');
    $bodydata = [
      'posisi' => RequestBody::post('posisi'),
      'keterangan' => RequestBody::post('keterangan'),
      'status' => RequestBody::post('status')
    ];

    $this->form_validation->set_data($bodydata);

    try {
      $this->form_validation->set_rules(
        'posisi',
        'Posisi',
        'required|trim',
        [
          'required' => '%s wajib diisi.'
        ]
      );

      $this->form_validation->set_rules(
        'keterangan',
        'Keterangan',
        'required|trim',
        [
          'required' => '%s wajib diisi.'
        ]
      );

      $this->form_validation->set_rules(
        'status',
        'Status',
        'required',
        [
          'required' => '%s wajib dipilih.'
        ]
      );

      if ($this->form_validation->run() == false) {
        throw new Exception("Validasi Gagal");
      }

      $data = PosisiEkspedisi::findOrFail(Hashid::singleDecode($id));
      $data->update($bodydata);
      $htmxEvent = [
        'action:success' => true
      ];
      $this->output->set_content_type("text/html")
        ->set_header('HX-Trigger: ' . json_encode($htmxEvent))
        ->set_output(Templ::component('pengaturan/components/edit_ekspedisi', [
          'alert' => Templ::component('components/success_alert', [
            'message' => 'Posisi Ekspedisi Berhasil Ditambahkan. Jendela akan ditutup dalam 2 detik.',
          ]),
          'data' => $data
        ]));
    } catch (\Throwable $th) {
      $this->output->set_content_type("text/html")
        ->set_output(Templ::component('pengaturan/components/edit_ekspedisi', [
          'alert' => Templ::component('components/exception_alert', [
            'message' => $th->getMessage(),
          ]),
          'id' => $id
        ]));
    }
  }
}
