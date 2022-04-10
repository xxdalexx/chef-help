# Testing

The PEST framework is preferred over PHPUnit, but PHPUnit can still be used if needed.

## Conventions

Testing functions can begin with _it_ or _test_ depending on the readability context.

DevSandbox.php file is used for on the fly testing that doesn't need to be commited to the project. The file doesn't
have the suffix of `test`, so it will not run with the full suite.
