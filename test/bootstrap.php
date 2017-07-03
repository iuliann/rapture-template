<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'rapture\\template\\adapter\\curly' => '/../src/Adapter/Curly.php',
                'rapture\\template\\adapter\\mustache' => '/../src/Adapter/Mustache.php',
                'rapture\\template\\adapter\\php' => '/../src/Adapter/Php.php',
                'rapture\\template\\adapter\\phtml' => '/../src/Adapter/Phtml.php',
                'rapture\\template\\definition\\templateinterface' => '/../src/Definition/TemplateInterface.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
// @codeCoverageIgnoreEnd
