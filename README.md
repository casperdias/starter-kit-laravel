<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

# **Laravel Vue (ShadCN) Starter**

A modern, feature-rich starter kit for building full-stack applications with Laravel backend and Vue 3 frontend, featuring ShadCN Vue components for beautiful, accessible UI development.

Fork from [Laravel Vue Starter Kit](https://awesomeopensource.com/project/elangosundar/awesome-README-templates)

## ✨ Features

- Full-Stack Architecture: Laravel 12 backend with Vue 3 frontend
- Modern UI Components: ShadCN Vue for accessible, customizable UI
- TypeScript Ready: Full TypeScript support for both frontend
- Authentication: Built-in authentication
- Role Access Managemnt: Simple User->Many Roles->Many Permissions
- Developer Experience: Hot reload, ESLint, Prettier, and TypeScript validation
- Dark Mode: Built-in dark/light theme support

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Node.js 23 or higher
- Composer
- MySQL/PostgreSQL/SQLite

### Installation

1. **Clone the project**

```bash
git clone https://github.com/casperdias/starter-kit-laravel.git
cd starter-kit-laravel
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Copy environment and generate key**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Update ENV variable (database, etc)**

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue_starter
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations**

```bash
php artisan migrate
```

6. **Build Asset**

```bash
npm run build
```

7. **Start**

```bash
composer dev
```

## 👥 Authors

- [casperdias](https://www.github.com/casperdias)

## 📖 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Vue Documentation](https://vuejs.org/guide/)
- [ShadCN Vue Component](https://www.shadcn-vue.com/docs/introduction)
- [VueUse](https://vueuse.org/guide/)
