<?php

use Rapture\Template\Template;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $tpl = new Template('test', ['one' => 1]);
        $this->assertEquals('test', $tpl->getName());
        $this->assertEquals([], $tpl->getInherited());
        $this->assertEquals(['one' => 1], $tpl->getParams());
        $this->assertEquals(['./views'], $tpl->getPaths());
        $this->assertEquals('phtml', $tpl->getExtension());
    }

    public function testInheritance()
    {
        $tpl = new Template('templates/index');
        $tpl->setPaths(['./views', __DIR__ . '/../views']);
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
        $tpl = new Template;
        $tpl->setPaths(__DIR__ . '/../views');

        $this->assertEquals(__DIR__ . '/../views' . '/templates/index.phtml', $tpl->name('templates/index'));
    }

    public function testEscape()
    {
        $tpl = new Template;

        $this->assertEquals('&lt;test&gt;', $tpl->e('<test>'));
    }

    public function testFilters()
    {
        $tpl = new Template;

        $tpl->setFilters([
            'exclamation' => function ($value) {
                return $value . '!';
            }
        ]);

        $this->assertEquals('HELLO WORLD!', $tpl->f('Hello World', 'strtoupper|exclamation'));
    }
}
