<p align="center"><a href="https://cashback.therightapps.com/" target="_blank"><img src="https://cashback.therightapps.com/admin-dashboard/images/logo.png" width="400"></a></p>

# Cashback Project Setup Guideline

There are two repositories for Cashback project.

+ [cashback-reborn](https://github.com/farrukhtrs/cashback-reborn) is for backend and for frontend has separate repository
  + Clone [Cashback-reborn](https://github.com/farrukhtrs/cashback-reborn) repository.
  + Clone 2nd repository in resources->views directory for frontend as frontend.
+ PHP version required 8.0
+ Install composer to add Laravel package
+ Run migration and seeder

---
php artisan migrate --seed
---

