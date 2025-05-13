<!DOCTYPE html>
<html>
<head>
    <title>AI 대화하기</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* 포인트 컬러 정의 */
        :root {
            --point-color: #00aadd; /* 선택한 파란색 계열 */
            --point-color-dark: #0088bb; /* 버튼 호버/활성 시 약간 어두운 색 */
            --ai-message-bg: #e9ecef; /* AI 메시지 배경색 (옅은 회색) */
            --ai-message-color: #212529; /* AI 메시지 글자색 */
        }

        body {
            padding-top: 20px; /* 상단 패딩 줄여서 공간 확보 */
            background-color: #f8f9fa; /* 배경색 */
            margin: 0; /* 기본 마진 제거 */
            padding-bottom: 20px; /* 하단 패딩 추가 */
        }

        .container {
            /* max-width 제거하여 전체 너비 사용 */
            /* max-width: 700px; */
            width: 100%; /* 너비를 100%로 설정 */
            padding-right: 15px; /* Bootstrap 기본 패딩 유지 */
            padding-left: 15px; /* Bootstrap 기본 패딩 유지 */
            margin-right: auto; /* 중앙 정렬 (필요하다면) */
            margin-left: auto; /* 중앙 정렬 (필요하다면) */
            background-color: #ffffff; /* 컨테이너 배경색 */
            padding-top: 30px; /* 상단 패딩 */
            padding-bottom: 30px; /* 하단 패딩 */
            border-radius: 8px; /* 모서리 둥글게 */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* 그림자 효과 */
        }

        /* 화면 너비가 넓을 때 컨테이너 최대 너비 제한 (선택 사항) */
        @media (min-width: 992px) { /* 예: 데스크탑 화면 */
            .container {
                max-width: 960px; /* 너무 넓어지지 않도록 최대 너비 설정 가능 */
            }
        }
         @media (min-width: 1200px) { /* 예: 더 넓은 데스크탑 화면 */
            .container {
                max-width: 1140px; /* 더 넓은 최대 너비 설정 가능 */
            }
        }


        .chat-box {
            border: 1px solid #ced4da; /* 테두리 색상 변경 */
            height: 500px; /* 채팅창 높이 증가 */
            overflow-y: auto; /* 내용 넘칠 때 스크롤 */
            padding: 15px; /* 내부 패딩 */
            margin-bottom: 20px; /* 하단 마진 */
            border-radius: 5px; /* 모서리 둥글게 */
            background-color: #ffffff; /* 채팅창 배경색 흰색으로 변경 */
            display: flex; /* 메시지 정렬을 위해 flexbox 사용 */
            flex-direction: column; /* 세로 방향 정렬 */
        }

        .message {
            margin-bottom: 15px; /* 메시지 간격 */
            padding: 10px; /* 메시지 내부 패딩 */
            border-radius: 15px; /* 말풍선 모양을 위해 모서리 더 둥글게 */
            max-width: 70%; /* 메시지 최대 너비 제한 */
            word-break: break-word; /* 긴 단어 줄바꿈 */
        }

        .user-message {
            background-color: var(--point-color); /* 포인트 컬러 적용 */
            color: white; /* 글자색 흰색 */
            margin-left: auto; /* 오른쪽 정렬 */
            margin-right: 0; /* 오른쪽 마진 제거 */
            border-bottom-right-radius: 5px; /* 말풍선 꼬리 부분처럼 보이도록 */
        }

        .ai-message {
            background-color: var(--ai-message-bg); /* AI 메시지 배경색 */
            color: var(--ai-message-color); /* AI 메시지 글자색 */
            margin-right: auto; /* 왼쪽 정렬 */
            margin-left: 0; /* 왼쪽 마진 제거 */
             border-bottom-left-radius: 5px; /* 말풍선 꼬리 부분처럼 보이도록 */
        }

        /* 오류 메시지 스타일 */
         .ai-message.error {
             background-color: #dc3545 !important; /* Bootstrap danger 색상 */
             color: white !important;
         }


        .input-group {
            margin-top: 10px; /* 입력창 위쪽 마진 */
        }

        /* Bootstrap 기본 버튼 스타일 오버라이드 */
        .btn-primary {
            background-color: var(--point-color); /* 포인트 컬러 적용 */
            border-color: var(--point-color); /* 포인트 컬러 적용 */
            transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out; /* 부드러운 전환 효과 */
        }

        .btn-primary:hover {
            background-color: var(--point-color-dark); /* 호버 시 약간 어두운 색 */
            border-color: var(--point-color-dark); /* 호버 시 약간 어두운 색 */
        }

        .btn-primary:focus,
        .btn-primary.focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 170, 221, 0.5); /* 포커스 시 그림자 효과 (포인트 컬러 기반) */
        }

        .btn-primary:active,
        .btn-primary.active,
        .show > .btn-primary.dropdown-toggle {
            background-color: var(--point-color-dark); /* 활성 시 약간 어두운 색 */
            border-color: var(--point-color-dark); /* 활성 시 약간 어두운 색 */
        }

        .btn-primary:disabled,
        .btn-primary.disabled {
            background-color: var(--point-color); /* 비활성 시 기본 포인트 컬러 유지 또는 다른 색상 */
            border-color: var(--point-color);
            opacity: 0.65; /* 비활성 시 투명도 적용 */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">GEMINI AI 대화</h2> <!-- 헤더 텍스트 변경 -->

        <div id="chatBox" class="chat-box">
            <!-- 대화 내용이 여기에 표시됩니다. -->
        </div>

        <div class="input-group mb-3">
            <input type="text" id="userInput" class="form-control" placeholder="여기에 메시지를 입력하세요..." aria-label="User Input">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="sendButton">전송</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle (Popper.js 포함) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            const chatBox = $('#chatBox');
            const userInput = $('#userInput');
            const sendButton = $('#sendButton');

            // HTML 이스케이프 함수 추가
            function escapeHtml(unsafe) {
                return unsafe
                     .replace(/&/g, "&amp;")
                     .replace(/</g, "&lt;")
                     .replace(/>/g, "&gt;")
                     .replace(/"/g, "&quot;")
                     .replace(/'/g, "&#039;")
                     .replace(/\//g, "&#x2F;");
            }

            function sendMessage() {
                const prompt = userInput.val().trim();
                if (prompt === '') {
                    return;
                }

                // 사용자 메시지 표시 - 이스케이프 적용
                // chatBox.append('<div class="message user-message">' + prompt + '</div>'); // 이전 코드
                chatBox.append('<div class="message user-message">' + escapeHtml(prompt) + '</div>'); // 수정된 코드
                chatBox.scrollTop(chatBox[0].scrollHeight);

                userInput.val('');
                userInput.prop('disabled', true);
                sendButton.prop('disabled', true);

                // AJAX 요청 보내기
                $.ajax({
                    url: "<?php echo base_url('api_controller/generate_response'); ?>",
                    method: "POST",
                    data: { prompt: prompt }, // prompt 변수 자체는 이스케이프 하지 않아도 됨 (data 속성은 객체로 전달)
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            // AI 응답 메시지 표시 - 이스케이프 적용
                            // chatBox.append('<div class="message ai-message">' + response.response + '</div>'); // 이전 코드
                            chatBox.append('<div class="message ai-message">' + escapeHtml(response.response) + '</div>'); // 수정된 코드
                        } else {
                            // 오류 메시지 표시 - 이스케이프 적용
                            // chatBox.append('<div class="message ai-message error">오류: ' + response.message + '</div>'); // 이전 코드
                             chatBox.append('<div class="message ai-message error">오류: ' + escapeHtml(response.message) + '</div>'); // 수정된 코드
                            console.error('API 오류:', response.message, response.details);
                        }
                         chatBox.scrollTop(chatBox[0].scrollHeight);
                    },
                    error: function(xhr, status, error) {
                         chatBox.append('<div class="message ai-message error">통신 오류 발생. 잠시 후 다시 시도해주세요.</div>');
                         console.error('AJAX 오류:', status, error, xhr.responseText, xhr);
                         chatBox.scrollTop(chatBox[0].scrollHeight);
                    },
                    complete: function() {
                        userInput.prop('disabled', false);
                        sendButton.prop('disabled', false);
                        userInput.focus();
                    }
                });
            }

            sendButton.on('click', sendMessage);

            userInput.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>
