# Rapture PHP Template

[![PhpVersion](https://img.shields.io/badge/php-5.4.0-orange.svg?style=flat-square)](#)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](#)

Simple templates for PHP.

## Requirements

- PHP v5.4.0

## Install

```
composer install iuliann/rapture-template
```

## Quick start

```php
$tpl = new Template('templates/home', ['nickname' => 'John']);
echo $tpl->render();
```

Layout
```html
<!doctype html>
<html>
<head>
	<title><?= $t->title ?></title>
</head>
<body>
	<!-- current scope -->
	<?php include $t->name('partials/header') ?>
	
    <div id="container">
    	<?= $t->content ?>
	</div>
	
    <!-- partial scope -->
    <?= $t->insert('partials/footer', ['title' => $t->title]) ?>
</body>
</html>
```

Template
```html
<?php $t->title = 'Test Title'; ?>

<div id="container">
	<!-- escape and filter -->
	<h2>Hello <?= $t->e($nickname, 'strtolower|ucfirst') ?>!</h2>
</div>
```

## About

### Author

Iulian N. `rapture@iuliann.ro`

### Testing

```
cd ./test && phpunit
```

### Credits

- http://platesphp.com/

### License

Rapture Template is licensed under the MIT License - see the `LICENSE` file for details.
