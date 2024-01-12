<p align="center"><a href="https://cashback.therightapps.com/" target="_blank"><img src="https://cashback.therightapps.com/admin-dashboard/images/logo.png"></a></p>

# Cashback Project Setup Guideline

## Server Requirements

+ curl
+ php 8.0
+ BCMath PHP Extension
+ Ctype PHP Extension
+ Fileinfo PHP extension
+ JSON PHP Extension
+ Mbstring PHP Extension
+ OpenSSL PHP Extension
+ PDO PHP Extension
+ Tokenizer PHP Extension
+ XML PHP Extension
+ GD PHP Extension

There are two repositories for Cashback project.

+ [cashback-reborn](https://github.com/farrukhtrs/cashback-reborn) is for backend and for frontend has separate repository
  + Clone [Cashback-reborn](https://github.com/farrukhtrs/cashback-reborn) repository.
  + Clone 2nd repository in resources->views directory for frontend as frontend.
+ Laravel framework 8

---

### .env File Setup

+ Copy .env.example file as .env on base patch
+ SESSION_DRIVER should be database.
+ QUEUE_CONNECTION should be database.
+ Firebase Credentials file should be in resources->views->frontend->vendors directory with the name of firebase-credentials.json
  + Put firebase credentials file path in . env file `FIREBASE_CREDENTIALS="resources/views/frontend/vendors/firebase-credentials.json"`
+ For strong password use PASSWORD_VALIDATION in .env file and type password validation format else comment this.
+ Use `TIMEZONE="Europe/London"` for custom timezone in complete project
+ Use `PAYMENT_METHOD="cashback"` in .env file to save in cashout meta table which represent to user make cashout from which website like 'cashback'

---

### .modules.yml File Setup

+ Copy module.yml.example file as module.yml on base path
+ .modules.yml file is used to enable/disable module like SMTP, Charities, Appeals.
+ File to use for enabling/disabling network importers (e.g., CJ, Awin, RevGlue)
  + Each networks have Settings and Importers
  + Settings display client_id and client_secrete
  + importers display `stores`, `vouchers`, `user cashbacks` and `categories`

---

### Installation

+ Install composer to add Laravel package
+ Now run below commands
  + `php artisan generate:key` // to generate new project key
  + `php artisan copy:frontend-asset` // to copy frontend assets in storage directory
  + `php artisan storage:link` // to display copied storage assets directory in public directory


### Page Module

+ There are three types of pages
  + System Pages
    + These pages use logics from controller and can't be deleted
      + Home Page Before Login
      + Home Page After Login
      + Blogs
      + Appeals
      + Categories
      + Charities
      + Contact Us
      + Stores
      + Offers
      + Trending
      + Vouchers
      + Maintenance
      + 401
      + 404
      + Search
  + Special Pages
    + These pages doesn't have any logic but can't be deleted. These pages create on client requirements
      + Privacy Policy
      + Cookies Policy
      + Terms and Conditions
      + Mobile APP
      + FAQs
      + Extensions
      + Work with Us
  + General Pages
    + Admin can create and delete from admin panel
