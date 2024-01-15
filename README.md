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
  + Clone 2nd repository in resources->views directory for frontend as `frontend` directory.
+ Laravel framework 8

---

### .env File Setup

+ Copy `.env.example` file as `.env` on base patch
+ `SESSION_DRIVER` should be database.
+ `QUEUE_CONNECTION` should be database.
+ Firebase Credentials file should be in resources->views->frontend->vendors directory with the name of `firebase-credentials.json`
  + Put firebase credentials file path in . env file `FIREBASE_CREDENTIALS="resources/views/frontend/vendors/firebase-credentials.json"`
+ For strong password use `PASSWORD_VALIDATION` in `.env` file and type password validation format else comment this.
+ Use `TIMEZONE="Europe/London"` for custom timezone in complete project
+ Use `PAYMENT_METHOD="cashback"` in .env file to save in cashout meta table which represent to user make cashout from which website like 'cashback'

---

### .modules.yml File Setup

+ Copy `module.yml.example` file as `module.yml` on base path
+ `.modules.yml` file is used to enable/disable module like SMTP, Charities, Appeals.
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

### Importers

There are seven networks that handle the import of stores, store cashbacks, network categories, user cashbacks, and vouchers. The `modules.yml` file allows importers to be enabled or disabled. If the importer is enabled and either the `stores_importer`, `categories_importer`, or `cashbacks_importer` has a value of 1, a cron job will run after a specified time set in cPanel to import stores.

+ Afrofiliate
+ Awin
+ Commission Junction (CJ)
+ Impact
+ Partnerize
+ RevGlue
+ Webgains

### SEO Module

For every page on the frontend of the website we have it has been SEO optimized. The flow of it’s working is follows.

+ In case of pages we can define specific rules of SEO for any page while creating or editing it, no matter it is system or special page. Same is the case while editing or adding categories, stores, appeals & blogs as we can define SEO rules for these things which were specific to them only.
+ We also have global SEO which can be accessed in CMS menu item dropdown and then clicking on SEO Rules. At this place we can define SEO rules for any frontend page in the way we want by providing the link of that page and writing the rules for SEO. If we have defined only one rule say Title in it for a page using link of that page & now we want to define description and keyword for it instead of creating new rule we will edit the already created and using the add button we can add rule in it.
+ This is global SEO module has higher priority over the local SEO rules in stores, categories etc. pages, if a global SEO rule is defined for a page the system will all together skip the local rules which lies behind in the priority. Basically the order of priority is first it will check global rules, if nothing found then local rules will be checked, if nothing founds there as well, it will simply use name and description of a said resource for name and description tags of SEO.
