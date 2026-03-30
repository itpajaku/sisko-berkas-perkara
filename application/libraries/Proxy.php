<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proxy
{
    protected $CI;
    protected $allowed_hosts = [];

    public function __construct($params = [])
    {
        $this->CI = &get_instance();

        if (isset($params['allowed_hosts'])) {
            $this->allowed_hosts = $params['allowed_hosts'];
        }
    }

    /**
     * Handle proxy request
     *
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param mixed  $body
     */
    public function request($url, $method = 'GET', $headers = [], $body = null)
    {
        $this->validate_url($url);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->format_headers($headers));
        }

        if (!empty($body) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return $this->error_response($error);
        }

        $status     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        curl_close($ch);

        $responseHeader = substr($response, 0, $headerSize);
        $responseBody   = substr($response, $headerSize);

        return $this->build_response($status, $responseHeader, $responseBody);
    }

    protected function validate_url($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            show_error('Invalid target URL', 400);
        }

        if (!empty($this->allowed_hosts)) {
            $host = parse_url($url, PHP_URL_HOST);
            if (!in_array($host, $this->allowed_hosts)) {
                show_error('Target host not allowed', 403);
            }
        }
    }

    protected function format_headers($headers)
    {
        $formatted = [];
        foreach ($headers as $key => $value) {
            $formatted[] = "{$key}: {$value}";
        }
        return $formatted;
    }

    protected function build_response($status, $headers, $body)
    {
        http_response_code($status);

        foreach (explode("\r\n", $headers) as $header) {
            if (
                stripos($header, 'Transfer-Encoding') === false &&
                stripos($header, 'Content-Length') === false
            ) {
                header($header);
            }
        }

        echo $body;
        exit;
    }

    protected function error_response($message)
    {
        http_response_code(500);
        echo json_encode([
            'status'  => false,
            'message' => $message
        ]);
        exit;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param mixed  $body
     * @return string
     */
    public function request_raw($url, $method = 'GET', $headers = [], $body = null)
    {
        $this->validate_url($url);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->format_headers($headers));
        }

        if (!empty($body) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return '';
        }

        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        // Pastikan memang text-based response
        if ($contentType && stripos($contentType, 'text/') !== false) {
            return (string) $response;
        }

        // Kalau bukan text, tetap kembalikan string mentah
        return (string) $response;
    }
}
