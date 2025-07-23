# 短網址 API 文檔

## 基礎 URL

```
http://your-domain.com/api/v1
```

## 認證

部分端點需要認證，請在請求頭中包含 `Authorization` 標頭：

```
Authorization: Bearer {token}
```

## 端點

### 創建短網址

**URL**: `/shorten`  
**方法**: `POST`  
**認證**: 可選

**請求參數**:

| 參數名 | 類型 | 必填 | 描述 |
|--------|------|------|------|
| original_url | string | 是 | 原始網址 |
| custom_code | string | 否 | 自定義短碼（4-20個字元，僅限字母、數字、連字符和底線） |
| expires_at | datetime | 否 | 過期時間（格式：Y-m-d H:i:s） |
| max_clicks | integer | 否 | 最大點擊次數 |

**成功響應**:

```json
{
    "success": true,
    "data": {
        "original_url": "https://example.com",
        "short_url": "http://your-domain.com/s/abc123",
        "short_code": "abc123",
        "expires_at": "2023-12-31 23:59:59",
        "max_clicks": 100,
        "created_at": "2023-07-23T12:00:00.000000Z"
    }
}
```

### 獲取短網址統計信息

**URL**: `/stats/{code}`  
**方法**: `GET`  
**認證**: 否

**URL 參數**:

| 參數名 | 類型 | 必填 | 描述 |
|--------|------|------|------|
| code | string | 是 | 短網碼 |

**成功響應**:

```json
{
    "success": true,
    "data": {
        "original_url": "https://example.com",
        "short_url": "http://your-domain.com/s/abc123",
        "short_code": "abc123",
        "total_clicks": 42,
        "created_at": "2023-07-23T12:00:00.000000Z",
        "expires_at": "2023-12-31 23:59:59",
        "stats": {
            "total_clicks": 42,
            "unique_visitors": 38,
            "browsers": {
                "Chrome": 30,
                "Firefox": 10,
                "Safari": 2
            },
            "platforms": {
                "Windows": 25,
                "macOS": 15,
                "Linux": 2
            },
            "devices": {
                "Desktop": 35,
                "Mobile": 7
            },
            "referrers": {
                "direct": 20,
                "https://google.com": 15,
                "https://twitter.com": 7
            },
            "clicks_by_date": {
                "2023-07-23": 5,
                "2023-07-24": 15,
                "2023-07-25": 22
            }
        }
    }
}
```

### 刪除短網址

**URL**: `/url/{code}`  
**方法**: `DELETE`  
**認證**: 是

**URL 參數**:

| 參數名 | 類型 | 必填 | 描述 |
|--------|------|------|------|
| code | string | 是 | 短網碼 |

**成功響應**:

```json
{
    "success": true,
    "message": "Short URL deleted successfully"
}
```

## 錯誤處理

所有錯誤響應都遵循以下格式：

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### 常見錯誤狀態碼

- `400`: 請求參數錯誤
- `401`: 未授權
- `403`: 禁止訪問
- `404`: 資源不存在
- `410`: 短網址已過期或已停用
- `422`: 驗證錯誤
- `500`: 伺服器錯誤

## 速率限制

API 限制每分鐘 60 個請求。超過限制將返回 `429 Too Many Requests` 錯誤。
