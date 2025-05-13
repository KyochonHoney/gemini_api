<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cors() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // 허용할 HTTP 메소드
    header('Access-Control-Allow-Headers: Content-Type'); // 허용할 헤더

    // OPTION 요청 처리 (Preflight Request)
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }
}
