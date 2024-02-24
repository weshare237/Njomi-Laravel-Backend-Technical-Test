# A simple banking API

- [A simple banking API](#a-simple-banking-api)
- [Getting Started](#getting-started)
  - [Brief](#brief)
  - [Requirements](#requirements)
  - [Quickstart](#quickstart)
    - [Optional Gitpod](#optional-gitpod)
- [Overview](#overview)
    - [The challenge](#the-challenge)
- [Thank you!](#thank-you)

# Getting Started

## Brief

While modern banks have evolved to serve a plethora of functions, at their core, banks must provide certain basic features. Today, your task is to build the basic REST API for one of those banks! Imagine you are designing a backend API for bank employees. It could ultimately be consumed by multiple frontends (web, iOS, Android etc).

## Requirements

-   [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
-   [sqlite](https://www.sqlite.org/)
-   [composer](https://getcomposer.org/)

## Quickstart

```
git clone https://github.com/weshare237/Njomi-Laravel-Backend-Technical-Test
cd Njomi-Laravel-Backend-Technical-Test
composer install
php artisan migrate
php artisan db:seed
php artisan test
php artisan scribe:generate
php artisan serve
```

### Optional Gitpod

If you can't or don't want to run and install locally, you can work with this repo in Gitpod. If you do this, you can skip the `clone this repo` part.

[![Open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#github.com/Njomi-Laravel-Backend-Technical-Test)

# Overview

### The challenge

-   Implement assignment using:
    -   Language: PHP
    -   Framework: Laravel
-   There should be API routes that allow them to:
    -   Authenticate users
    -   Create a new bank account for a customer, with an initial deposit amount. A single customer may have multiple bank accounts.
    -   Transfer amounts between any two accounts, including those owned by different customers.
    -   Retrieve balances for a given account.
    -   Retrieve transfer history for a given account.
-   All endpoints should only be accessible if an API key is passed as a header.
-   All role-based endpoints should require authentication.
-   Write tests for your business logic.
-   Provide a documentation (published with Postman) that says what endpoints are available and the kind of parameters they expect.
-   You are expected to design all required models and routes for your API.

# Thank you!

If you appreciated this, feel free to follow me or donate!

[![ITutorix-CS YouTube](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white)](https://www.youtube.com/@itutorix)
[![Duclair FOPA KUETE Linkedin](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/duclair-fopa/)
