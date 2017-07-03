<?php

namespace Rapture\Template\Adapter;

use Psr\Http\Message\StreamInterface;
use Rapture\Template\Definition\TemplateInterface;

/**
 * Rapture template engine
 *
 * @package Rapture\Template
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class Mustache implements TemplateInterface
{
    protected $body = '';

    protected $params = [];

    protected $options = [];

    public function __construct($body, $params = [], $options = [])
    {
        $this->body    = $body;
        $this->params  = $params;
        $this->options = $options;
    }

    public function render():string
    {
        $contents = '';
        if (is_string($this->body)) {
            $contents = $this->body;
        }
        elseif ($this->body instanceof \SplFileInfo) {
            $contents = file_get_contents($this->body->getRealPath());
        }
        elseif ($this->body instanceof StreamInterface) {
            $contents = $this->body->getContents();
        }

        $m = new \Mustache_Engine($this->options);

        return $m->render($contents, $this->params);
    }
}
