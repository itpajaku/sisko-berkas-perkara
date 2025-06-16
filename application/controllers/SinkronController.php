<?php
defined('BASEPATH') or exit;

use App\Libraries\MethodFilter;
use App\Libraries\RequestBody;
use App\Libraries\Templ;

class SinkronController extends APP_Controller
{
  public function berkas_page()
  {
    MethodFilter::must('get');
    Templ::render('sinkron/sinkron_berkas_page')->layout('layouts/main_layout', ['title' => 'Sinkron Berkas Gugatan']);
  }

  public function berkas_action()
  {
    MethodFilter::must('post');
    try {
      $tahun = RequestBody::post('tahun');
      $opsi = RequestBody::post('opsi');

      // Deteksi PHP binary
      $phpPath = PHP_BINARY;
      if (!file_exists($phpPath)) {
        throw new Exception("PHP executable tidak ditemukan");
      }

      $logfile = APPPATH . 'logs/import_log_' . date('d_m_Y') . '.log';
      if (file_exists($logfile)) {
        unlink($logfile);
      }
      // Lokasi file CLI
      $scriptPath = realpath(FCPATH . '../db/transfers/transfer_berkas.php');
      if (!file_exists($scriptPath)) {
        throw new Exception("Script CLI tidak ditemukan: $scriptPath");
      }

      $phpPath = 'php';
      $scriptPath = FCPATH . '../db/transfers/transfer_berkas.php';
      $cmd = "$phpPath $scriptPath --tahun=$tahun --opsi=$opsi 2>&1";
      // $output = shell_exec($cmd);
      // if ($output == false) {
      //   file_put_contents(APPPATH . 'logs/cli_debug_output.log', "Its False");
      // } else {
      //   file_put_contents(APPPATH . 'logs/cli_debug_output.log', $output);
      // }
      file_put_contents($logfile, "Proses import dimulai...\n");

      if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        // Untuk Windows
        pclose(popen("start /B " . $cmd, "r"));
      } else {
        // Untuk Linux/Mac
        exec("$cmd > /dev/null 2>&1 &");
      }
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "info",
            "message" => "Proses sedang berjalan. Mohon tunggu dan jangan ditutup"
          ]
        ]))
        ->set_output(Templ::component("sinkron/sinkron_result"));
    } catch (\Throwable $th) {
      $this->output->set_output(Templ::component("components/exception_alert", ["message" => $th->getMessage()]));
    }
  }

  public function migrate_berkas_gugatan()
  {
    echo "Hello World";
  }

  public function stream_log()
  {
    set_time_limit(0);
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');

    $i = 1;
    $filename = APPPATH . 'logs/import_log_' . date('d_m_Y') . '.log';
    sleep(5);

    while (true) {
      clearstatcache();
      if (connection_aborted()) break;

      if (file_exists($filename)) {
        $log = file_get_contents($filename);
        $lines = explode("\n", trim($log));
        if (count($lines) < $i) {
          echo "event: message_closed\n";
          echo "data: <p>Finish reading logs</p>\n\n";
          ob_flush();
          flush();
          break;
        }

        echo "event: message\n";
        echo "data: <p>{$lines[$i - 1]}</p>\n\n";
      } else {
        if ($i > 5) {
          echo "event: message_closed\n";
          echo "data: <p>Message was closed</p>\n\n";
          ob_flush();
          flush();
          break;
        }
        echo "event: message\n";
        echo "data: <p>File not found in $filename</p>\n\n";
      }

      ob_flush();
      flush();
      usleep(150000);
      $i++;
    }
  }

  public function akta_page()
  {
    MethodFilter::must('get');
    Templ::render('sinkron/sinkron_akta_page')->layout('layouts/main_layout', ['title' => 'Sinkron Berkas Gugatan']);
  }

  public function akta_action()
  {
    MethodFilter::must('post');
    try {
      $tahun = RequestBody::post('tahun');
      $opsi = RequestBody::post('opsi');

      // Deteksi PHP binary
      $phpPath = PHP_BINARY;
      if (!file_exists($phpPath)) {
        throw new Exception("PHP executable tidak ditemukan");
      }

      $logfile = APPPATH . 'logs/import_log_' . date('d_m_Y') . '.log';
      if (file_exists($logfile)) {
        unlink($logfile);
      }
      // Lokasi file CLI
      $scriptPath = realpath(FCPATH . '../db/transfers/transfer_akta.php');
      if (!file_exists($scriptPath)) {
        throw new Exception("Script CLI tidak ditemukan: $scriptPath");
      }

      $phpPath = 'php';
      $scriptPath = FCPATH . '../db/transfers/transfer_akta.php';
      $cmd = "$phpPath $scriptPath --tahun=$tahun --opsi=$opsi 2>&1";
      // $output = shell_exec($cmd);
      // if ($output == false) {
      //   file_put_contents(APPPATH . 'logs/cli_debug_output.log', "Its False");
      // } else {
      //   file_put_contents(APPPATH . 'logs/cli_debug_output.log', $output);
      // }
      file_put_contents($logfile, "Proses import dimulai...\n");

      if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        // Untuk Windows
        pclose(popen("start /B " . $cmd, "r"));
      } else {
        // Untuk Linux/Mac
        exec("$cmd > /dev/null 2>&1 &");
      }
      $this->output
        ->set_header("HX-Trigger: " . json_encode([
          "htmx:toastr" => [
            "level" => "info",
            "message" => "Proses sedang berjalan. Mohon tunggu dan jangan ditutup"
          ]
        ]))
        ->set_output(Templ::component("sinkron/sinkron_result"));
    } catch (\Throwable $th) {
      $this->output->set_output(Templ::component("components/exception_alert", ["message" => $th->getMessage()]));
    }
  }
}
