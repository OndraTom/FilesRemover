# FilesRenamer
Renames files in directory with given regular expression and substitution.

## Basic Useage

```php
// We want to delete numbers from the files names.
$renamer = new FilesRenamer(__DIR__ . '/files', '/\d*/', '');

// We will also rename the files in the subdirectories.
$renamer->setRecursiveMode(true);

// We will first test if the result is correct.
$renamer->setDebugMode(true);

$renamer->run();
```
