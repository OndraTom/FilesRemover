# FilesRenamer
Renames files in directory with a given renaming function.

## Installation
The best way how to install is to [download a latest package](https://github.com/OndraTom/FilesRenamer/releases)
or use a Composer:

```
php composer.phar require --dev totem/files-renamer
```

FileRenamer requires PHP 7.0.0 or later.

## Basic Useage

```php
// We want to delete numbers from the file names.
$renamer = new FilesRenamer(
	__DIR__ . '/files',
	function ($fileName) {
		return preg_replace('/\d*/', '', $fileName);
	}
);

// We will also rename the files in the subdirectories.
$renamer->setRecursiveMode(true);

// We will first test if the result is correct.
$renamer->setDebugMode(true);

$renamer->run();
```
