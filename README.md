# Project Setup & Review Guide

This guide will help you set up and run the project locally, including installing dependencies, running migrations, importing category data, and generating embeddings.

## 🚀 Review & Setup Steps

### 1. Install PHP Dependencies
```bash
composer install
```

### 2. Run Database Migrations
```bash
php artisan migrate
```

### 3. Import Categories
Import the category list from the provided `Categories.xlsx` file.

```bash
php artisan import:categories Categories.xlsx
```

> **Note:** Ensure your `.env` has a valid `BASE_PATH` pointing to the folder where `Categories.xlsx` is located like `/www/Laravel-CSV/public/assets/ImportFiles/`.

### 4. Generate Category Embeddings
This step requires a valid Cohere API key.

```bash
php artisan generate:category-embeddings
```

> Set `COHERE_API_KEY` in your `.env`.  
> Get your key here: https://dashboard.cohere.com/api-keys

### 5. Start the Development Server
```bash
php artisan serve
```
