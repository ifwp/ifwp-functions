<?php

if(!class_exists('IFWP_Remote_Response')){
    class IFWP_Remote_Response {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($response = [], $raw_response = null){
            if(ifwp_seems_response($response)){
                $this->from_array($response);
                $this->raw_data = $this->data;
                $this->raw_response = $raw_response;
                $this->maybe_json_decode();
                $this->maybe_merge();
            } else {
                $this->message = __('Invalid object type.');
                $this->raw_response = is_null($raw_response) ? $response : $raw_response;
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function code(){
            return $this->code;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function data(){
            return $this->data;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function message(){
            return $this->message;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function success(){
            return $this->success;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function raw_data(){
            return $this->raw_data;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function raw_response(){
            return $this->raw_response;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function to_array(){
            return [
                'code' => $this->code,
                'data' => $this->data,
                'message' => $this->message,
                'success' => $this->success,
            ];
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private $code = 500, $data = null, $message = '', $raw_data = null, $raw_response = null, $success = false;

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function from_array($response = []){
            $this->code = $response['code'];
            $this->data = $response['data'];
            $this->message = $response['message'];
            $this->success = $response['success'];
            if(!$this->message){
                $this->message = ($this->success ? 'OK' : __('Something went wrong.'));
            }
            if($this->success !== ifwp_is_successful($this->code)){
                $this->code = ($this->success ? 200 : 500);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function maybe_json_decode(){
            if(ifwp_seems_json($this->data)){
                $this->data = json_decode($this->data);
                if(json_last_error() !== JSON_ERROR_NONE){
                    $this->code = 500;
                    $this->message = json_last_error_msg();
                    $this->success = false;
                }
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function maybe_merge(){
            if(ifwp_seems_remote_response($this->data)){
                $this->from_array($this->data);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
