# xbtitfm-to-unit3d
An artisan package to import a xbtitFM database into UNIT3D.

## Install

Via Composer

```bash
$ composer require hdinnovations/xbtitfm-to-unit3d --dev
```

To install, just:
- Install this package from your UNIT3D project root using the command above.
- Create a new database in MySql and import you old XbtitFM DB to it.

## Usage

For instructions on usage, run:

```bash
php artisan unit3d:from-xbtitfm --help
```

Example:
To import your old XbtitFM DB run:

```bash
php artisan unit3d:from-xbtitfm --database='Your DB Name HERE' --username='Your DB Username Here'
--password='Your DB Password Here'
```
