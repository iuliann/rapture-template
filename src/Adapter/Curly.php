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
class Curly implements TemplateInterface
{
    protected $body = '';

    protected $params = [];

    public function __construct($body, $params = [])
    {
        $this->body   = $body;
        $this->params = $params;
    }

    public function render()
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

        $replacements = [];
        foreach ((array)$this->params as $key => $value) {
            $replacements["{{{$key}}}"] = $value;
        }

        return str_replace(array_keys($replacements), $replacements, $contents);
    }
}
