<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conversation_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    // 데이터베이스에 대화 기록 저장
    public function save_conversation($user_query, $ai_response)
    {
        $data = array(
            'user_query' => $user_query,
            'ai_response' => $ai_response,
            'timestamp' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('conversations', $data); // 'conversations' 테이블에 삽입
    }

    // 데이터베이스에서 대화 기록 조회 (필요하다면 구현)
    // public function get_conversations() { ... }

    // Gemini API 호출 함수
    public function call_gemini_api($prompt)
    {
        $api_key = 'SECRET_KEY'; // 실제 Gemini API 키로 교체
        $api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$api_key}";

        $payload = json_encode([
            "contents" => [[
                "parts" => [["text" => $prompt]]
            ]]
        ]);

        $ch = curl_init($api_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 개발 단계에서만 사용, 실제 서비스에서는 SSL 인증서 검증 필요

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            // cURL 오류 처리
            log_message('error', 'cURL Error: ' . $curl_error);
            return ['error' => 'API 호출 중 오류 발생: ' . $curl_error];
        }

        if ($http_code != 200) {
             // HTTP 오류 처리 (예: API 키 오류, 요청 형식 오류 등)
            log_message('error', 'API HTTP Error: ' . $http_code . ' Response: ' . $response);
            return ['error' => 'API 응답 오류 발생: ' . $http_code, 'details' => $response];
        }


        $result = json_decode($response, true);

        // Gemini API 응답 형식에 따라 결과 파싱
        // 실제 응답 구조를 확인하여 파싱 로직 수정 필요
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return ['success' => true, 'text' => $result['candidates'][0]['content']['parts'][0]['text']];
        } else {
             log_message('error', 'Unexpected API response format: ' . $response);
            return ['error' => 'API 응답 형식 오류', 'details' => $response];
        }
    }
}
