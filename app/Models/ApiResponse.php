<?php

namespace App\Models;

use Illuminate\Http\Response;

class ApiResponse
{
    private $_data;
    private $_success;
    private $_status;
    private $_info;
    public function __construct($data, $success = TRUE, $info = NULL, $status = 200)
    {
        $this->setData($data);
        $this->setSuccess($success);
        $this->setStatus($status);
        $this->setInfo($info);
    }
    public function getData() {
        return $this->_data;
    }
    public function getSuccess() {
        return $this->_success;
    }
    public function getStatus() {
        return $this->_status;
    }
    public function getInfo() {
        return $this->_info;
    }
    public function setData($data) {
        $this->_data = $data;
    }
    public function setSuccess($success) {
        if ($success === TRUE || $success === 'true') {
            $this->_success = TRUE;
        } else {
            $this->_success = FALSE;
        }
    }
    public function setStatus($status) {
        $status = (int) $status;
        if (is_nan($status)) { $status = 0; }
        $this->_status = $status;
    }
    public function setInfo($info) {
        $this->_info = $info;
    }
    public function response() {
        $response = new Response([
            "success" =>  $this->getSuccess(),
            "data" =>  $this->getData(),
            "info" =>  $this->getInfo()
        ], $this->getStatus());
        return $response;
    }
}
