# gemini_api

/application/models/Conversation_model.php 에서 Gemini에서 받은 Key값 변경

https://github.com/user-attachments/assets/70aa119c-7ee7-4bdf-ace8-595df61263a4


`gpt`DB 생성 후 

```
CREATE TABLE conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_query TEXT,
    ai_response TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
쿼리 실행
