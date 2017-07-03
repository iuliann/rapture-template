<?php

use Rapture\Template\Adapter\Phtml;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $tpl = new Phtml('test', ['one' => 1]);
        $this->assertEquals('test', $tpl->getName());
        $this->assertEquals([], $tpl->getInherited());
        $this->assertEquals(['one' => 1], $tpl->getParams());
        $this->assertEquals(['./views'], $tpl->getPaths());
    }

    public function testInheritance()
    {
        $tpl = new Phtml('templates/index');
        $tpl->addPaths(['./views', __DIR__ . '/../views']);
        $tpl->inherit('layouts/default');

        $result = '<!doctype html>
<html>
<head>
<title>Test</title>
</head>
<body>
<div id="container">Hello world!</div>
<footer>Copyright 2017</footer>
</body>
</html>
';

        $this->assertEquals($result, $tpl->render());
    }

    public function testName()
    {
        $tpl = new Phtml;
        $tpl->addPaths(__DIR__ . '/../views');

        $this->assertEquals(realpath(__DIR__ . '/../views/templates/index.phtml'), realpath($tpl->name('templates/index')));
    }

    public function testEscape()
    {
        $tpl = new Phtml;

        $this->assertEquals('&lt;test&gt;', $tpl->e('<test>'));
    }

    public function testFilters()
    {
        $tpl = new Phtml;

        $tpl->addFilters([
            'exclamation' => function ($value) {
                return $value . '!';
            }
        ]);

        $this->assertEquals('HELLO WORLD!', $tpl->f('Hello World', 'strtoupper|exclamation'));
    }
}
