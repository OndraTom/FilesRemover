# FilesRenamer
Renames files in directory with a given renaming function.

## Basic Useage

```php
// We want to delete numbers from the file names.
$renamer = new FilesRenamer(
	__DIR__ . 'files',
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
