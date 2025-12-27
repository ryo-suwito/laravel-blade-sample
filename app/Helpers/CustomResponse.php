<?php
/**
 * Created by PhpStorm.
 * User: Lorentzo
 * Date: 28-Jul-21
 * Time: 14:52
 */

namespace App\Helpers;


use Psr\Http\Message\ResponseInterface;

class CustomResponse {

    const STATUS_CODE_OK = 6000;
    const STATUS_CODE_ITEM_NOT_FOUND = 7014;

    public $guzzle_response = null;
    public $http_status_code = 0;
    public $body_string = "";
    public $is_ok = false;

    public $time = 0;
    public $status_code = 0;
    public $status_message = "";
    public $result = null;

    public function __construct($response) {
        if ($response) {
            $this->guzzle_response = $response;
            $this->http_status_code = $response->getStatusCode();
            $this->body_string = $response->getBody()->getContents();
            $response->getBody()->rewind();

            $body_object = json_decode($this->body_string);

            if (isset($body_object->status_code) && $body_object->status_code == self::STATUS_CODE_OK) {
                $this->is_ok = true;
            } else {
                $this->is_ok = false;
            }

            $this->time = isset($body_object->time) ? $body_object->time : $this->time;
            $this->status_code = isset($body_object->status_code) ? $body_object->status_code : $this->status_code;
            $this->status_message = isset($body_object->status_message) ? $body_object->status_message : $this->status_message;
            $this->result = isset($body_object->result) ? $body_object->result : $this->result;
        } else {
            // Do nothing.
        }
    }

}