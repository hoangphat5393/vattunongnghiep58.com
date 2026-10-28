@echo off
chcp 65001 > nul
title Xuat Database MySQL - VATTUNONGNGHIEP58
echo ==============================================================
echo   DANG DONG BO VA XUAT FILE SQL CHUAN MYSQL TU POSTGRESQL...
echo ==============================================================
echo.

cd /d "%~dp0"
php artisan db:sync-to-mysql

echo.
echo ==============================================================
echo   HOAN TAT! File SQL duoc luu tai:
echo   database\vattunongnghiep58_mysql_latest.sql
echo ==============================================================
echo.
pause
