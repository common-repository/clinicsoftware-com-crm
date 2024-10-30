<?php

class clinicsoftwarecom_api
{
    private $businessAlias = '';
    private $clientKey;
    private $clientSecret;
    public $apiURL = null;
    private $last_result = null;
    private $last_status = null;
    private $last_error = null;
    public $log_filename = null;
    private $debug = false;

    public function __construct($clientKey, $clientSecret, $businessAlias, $apiURL)
    {
        $this->clientKey = $clientKey;
        $this->clientSecret = $clientSecret;
        $this->apiURL = 'https://' . $apiURL . '/api_business';
        $this->log_filename = __DIR__ . '/log.txt';

        file_put_contents($this->log_filename, '');

        if (!empty($businessAlias)) {
            $this->businessAlias = $businessAlias;
        }

        if ($this->debug) {
            $this->clearLog();
        }
    }

    public function __destruct()
    {

    }

    public function setDebug($debug)
    {
        $this->debug = $debug;

        if ($this->debug) {
            $this->clearLog();
        }
    }

    public function setURL($url)
    {
        $this->apiURL = $url;
    }

    public function getLastResult()
    {
        return $this->last_result;
    }

    public function getLastStatus()
    {
        return $this->last_status;
    }

    public function getLastError()
    {
        return $this->last_error;
    }

    public function call($params = array())
    {
        $this->last_result = null;
        $this->last_status = null;
        $this->last_error = null;

        if(empty($this->businessAlias) || empty($this->clientKey) || empty($this->clientSecret) || empty($this->apiURL)){
            $this->last_error = 'API Connection failed, please check your settings';
            return null;
        }

        $params['business_client_alias'] = $this->businessAlias;
        $params['api_client_key'] = $this->clientKey;
        $params['api_client_time'] = time();
        $params['api_client_salt'] = uniqid(mt_rand(), true);
        $params['api_client_hash'] = hash('sha256', $params['api_client_salt'] . $params['api_client_time'] . $this->clientSecret);


        $start = microtime(true);

        if ($this->debug) {
            $this->writeLog("Call to {$this->apiURL}: " . json_encode($params));
        }

        $args = array(
            'body'        => $params,
            'timeout'     => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => [
                'User-Agent' => 'ClinicSoftware API PHP-SDK/1.6',
            ],
        );
        $call = wp_remote_post($this->apiURL, $args);
        if(is_wp_error($call)){
            $this->last_error = "Failed to reach ClinicSoftware API, please check again the settings and keys.";
            return null;
        }
        $response = json_decode($call['body'], true);

        if (empty($response)) {
            $this->last_error = "Failed decoding JSON response: {$response}";
            return null;
        }

        $this->last_result = $response;
        $this->last_status = $response['status'];

        if ($response['status'] == 'error') {
            $this->last_error = "API Error: {$response['message']}";
            return null;
        }

        return empty($response['data']) ? null : $response['data'];
    }

    public function getStatus()
    {
        $params = array();
        $params['action'] = 'check_status';
        return $this->call($params);
    }

    public function getLeadCustomFields()
    {
        $params = array();
        $params['action'] = 'get_lead_custom_fields';
        return $this->call($params);
    }

    public function getMapping()
    {
        $params = array();
        $params['action'] = 'get_mapping';
        return $this->call($params);
    }

    public function addLead($data)
    {
        $params = array();
        $params['action'] = 'add_lead';
        $params['data'] = json_encode($data);
        return $this->call($params);
    }

    public function readLog()
    {
        if (!file_exists($this->log_filename)) return '';

        $fh = fopen($this->log_filename, 'r');
        if (false === $fh) return '';

        $contents = fread($fh, filesize($this->log_filename));
        fclose($fh);

        return $contents;
    }

    private function writeLog($message)
    {
        if (!file_exists($this->log_filename)) return;

        $fh = fopen($this->log_filename, 'a');
        if (false === $fh) return;

        fwrite($fh, "{$message}\n\n");
        fclose($fh);
    }

    private function clearLog()
    {
        if (!file_exists($this->log_filename)) return;

        $fh = fopen($this->log_filename, 'w');
        if (false === $fh) return;

        fclose($fh);
    }

}