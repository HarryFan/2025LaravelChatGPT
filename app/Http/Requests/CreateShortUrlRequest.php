<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateShortUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 任何人都可以創建短網址，但如果需要登入才能創建，可以改為 return auth()->check();
        return true;
    }

    public function rules(): array
    {
        return [
            'original_url' => [
                'required',
                'url:http,https',
                'max:2048',
            ],
            'short_code' => [
                'nullable',
                'alpha_dash',
                'min:3',
                'max:50',
                Rule::unique('short_urls', 'short_code')
            ],
            'max_clicks' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000000',
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => '請輸入要縮短的網址',
            'original_url.url' => '請輸入有效的網址',
            'original_url.max' => '網址長度不能超過 2048 個字元',
            'short_code.alpha_dash' => '自訂短碼只能包含字母、數字、破折號和下劃線',
            'short_code.min' => '自訂短碼至少需要 3 個字元',
            'short_code.max' => '自訂短碼不能超過 50 個字元',
            'short_code.unique' => '此短碼已被使用，請選擇其他短碼',
            'max_clicks.integer' => '點擊次數限制必須是整數',
            'max_clicks.min' => '點擊次數限制至少為 1 次',
            'max_clicks.max' => '點擊次數限制不能超過 1,000,000 次',
            'expires_at.date' => '請輸入有效的日期時間',
            'expires_at.after' => '過期時間必須是未來的時間',
        ];
    }

    public function prepareForValidation()
    {
        // 如果提供了 short_code，轉換為小寫
        if ($this->has('short_code') && $shortCode = $this->input('short_code')) {
            $this->merge([
                'short_code' => strtolower($shortCode),
            ]);
        }
    }
}
