# Deployment Fix Guide

## Fixed Issues

### 1. Trait "Maatwebsite\Excel\Concerns\Importable" not found
**Solution:** Removed unused `Importable` trait from `McuImport.php`
- File: `app/Imports/McuImport.php`
- Removed: `use Maatwebsite\Excel\Concerns\Importable;` and `Importable` from class usage
- Kept: `SkipsErrors` trait which is required for error handling

### 2. Class "PhpOffice\PhpSpreadsheet\Reader\Csv" not found
**Solution:** Fixed Excel configuration to avoid early class loading
- File: `config/excel.php`
- Removed: `use PhpOffice\PhpSpreadsheet\Reader\Csv;`
- Changed: `Csv::GUESS_ENCODING` to `'UTF-8'` (line 130)
- Added: Environment variable support for cache path (line 326)

### 3. Undefined constant Maatwebsite\Excel\Excel::XLSX
**Solution:** Replaced all Excel class constants with string values
- File: `config/excel.php`
- Removed: `use Maatwebsite\Excel\Excel;`
- Replaced all `Excel::` constants with their string equivalents:
  - `Excel::XLSX` → `'Xlsx'`
  - `Excel::CSV` → `'Csv'`
  - `Excel::DOMPDF` → `'Dompdf'`
  - etc.

## Environment Variables for Production

Add to your production `.env` file:

```env
# Excel Configuration
EXCEL_CACHE_PATH=/tmp/laravel-excel
```

## Required Commands for Deployment

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Clear application cache
php artisan cache:clear

# Optimize autoloader
composer dump-autoload --optimize
```

## File Changes Made

1. **app/Imports/McuImport.php**
   - Removed `Importable` trait usage
   - Import functionality still works normally

2. **config/excel.php**
   - Removed early class loading
   - Added environment variable support
   - Set default UTF-8 encoding

3. **.env.example**
   - Added Excel cache path configuration

## Verification

- ✅ Import MCU functionality works without "Importable" trait error
- ✅ Excel configuration loads without PhpSpreadsheet class error
- ✅ Excel constants resolved without class loading issues
- ✅ Configuration caching works properly (`php artisan config:cache`)
- ✅ All routes accessible (tested with `php artisan route:list`)

The import functionality remains fully operational after these fixes.