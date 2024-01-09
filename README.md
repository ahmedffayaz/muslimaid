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

+ SESSION_DRIVER should be database.
+ QUEUE_CONNECTION should be database.
+ Firebase Credentials file should be in resources->views->frontend->vendors directory with the name of firebase-credentials.json
  + Put firbase credentials file path in . env file FIREBASE_CREDENTIALS="resources/views/frontend/vendors/firebase-credentials.json"
+ For strong password use PASSWORD_VALIDATION in .env file and type password validation format else comment this.
+ Use TIMEZONE="Europe/London" for custom timezone in complete project
+ Use PAYMENT_METHOD="cashback" in .env file to save in cashout meta table which represent to user make cashout from which website like 'cashback'

---

### .modules.yml File Setup

+ .modules.yml file is used for to enable/disable module like SMTP, Charities, Appeals.
+ File would use for enable/disable networks importer like CJ, Awin, RevGlue
  + Each networks have Settings and Importers
  + Settings display client_id and client_secrete
  + importers display stores, vouchers, user cashbacks and categories

---

+ Install composer to add Laravel package
+ Copy module.yml.example file as module.yml on base path
+ Copy .env.example file as .env on base patch
+ Now run below commands
  + php artisan generate:key // to generate new project key
  + php artisan copy:frontend-asset // to copy frontend assets in storage directory
  + php artisan storage:link // to display copied storage assets directory in public directory
