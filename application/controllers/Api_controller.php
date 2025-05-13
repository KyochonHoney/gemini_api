<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_controller extends CI_Controller { // 클래스 이름은 파일 이름과 대소문자 일치 [[10]](https://opentutorials.org/module/327/3827)

    public function __construct()
    {
        parent::__construct();
        // Conversation_model 로드
        $this->load->model('Conversation_model');
    }

    // 기본 페이지 로드 (대화 인터페이스 View)
    public function index()
    {
        $this->load->view('conversation_view'); // conversation_view.php 파일 로드
    }

    // AI 응답을 받는 API 엔드포인트 (AJAX 등으로 호출될 예정)
    public function generate_response()
    {
        // POST 요청인지 확인
        if ($this->input->method() === 'post')
        {
            // 사용자로부터 'prompt' 데이터 받기
            $user_query = $this->input->post('prompt', TRUE); // XSS 필터링 적용
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');
            if (!empty($user_query)) {
                // Model의 API 호출 함수 실행
                $api_result = $this->Conversation_model->call_gemini_api($user_query);

                if (isset($api_result['success']) && $api_result['success']) {
                    $ai_response = $api_result['text'];

                    // 데이터베이스에 대화 기록 저장 (선택 사항)
                    $this->Conversation_model->save_conversation($user_query, $ai_response);

                    // 응답 결과를 JSON 형식으로 반환
                    $this->output
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['status' => 'success', 'response' => $ai_response]));
                } else {
                    // API 호출 실패 또는 응답 오류 처리
                    $error_message = isset($api_result['error']) ? $api_result['error'] : '알 수 없는 API 오류 발생';
                    $this->output
                         ->set_content_type('application/json')
                         ->set_status_header(500) // Internal Server Error
                         ->set_output(json_encode(['status' => 'error', 'message' => $error_message]));
                }
            } else {
                 // prompt가 비어있을 경우
                 $this->output
                      ->set_content_type('application/json')
                      ->set_status_header(400) // Bad Request
                      ->set_output(json_encode(['status' => 'error', 'message' => '프롬프트 내용이 비어 있습니다.']));
            }
        } else {
            // POST 요청이 아닐 경우
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(405) // Method Not Allowed
                 ->set_output(json_encode(['status' => 'error', 'message' => 'POST 요청만 허용됩니다.']));
        }
    }
}
