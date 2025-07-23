<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling.# Laravel 短網址服務

<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1X/Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
    <a href="https://github.com/yourusername/laravel-short-url/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Laravel Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 關於 Laravel 短網址服務

這是一個基於 Laravel 12 的短網址服務，提供 URL 縮短、重定向、點擊統計等功能。

## 功能特點

- 將長網址轉換為短網址
- 自定義短碼
- 設置過期時間
- 設置最大點擊次數
- 詳細的點擊統計
- 支持用戶認證
- RESTful API

## 系統需求

- PHP 8.2+
- Composer
- MySQL 8.0+ / PostgreSQL / SQLite / SQL Server
- Node.js & NPM (前端資源編譯)

## 安裝步驟

1. 克隆代碼庫

```bash
git clone https://github.com/yourusername/laravel-short-url.git
cd laravel-short-url
```

2. 安裝 PHP 依賴

```bash
composer install
```

3. 複製環境文件

```bash
cp .env.example .env
```

4. 生成應用密鑰

```bash
php artisan key:generate
```

5. 配置數據庫

編輯 `.env` 文件，設置數據庫連接信息：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_short_url
DB_USERNAME=root
DB_PASSWORD=
```

6. 運行數據庫遷移

```bash
php artisan migrate
```

7. 安裝前端依賴

```bash
npm install
npm run build
```

8. 啟動開發服務器

```bash
php artisan serve
```

現在，您可以訪問 `http://localhost:8000` 來使用短網址服務。

## API 文檔

詳細的 API 文檔請參見 [API.md](docs/API.md)

## 貢獻指南

歡迎提交問題和拉取請求！請確保您的代碼符合 PSR-2 編碼標準。

## 安全漏洞

如果您發現安全漏洞，請發送電子郵件至您的電子郵件地址。所有安全漏洞將被及時處理。

## 許可證

[MIT 許可證](LICENSE)

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
