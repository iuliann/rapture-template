<?php

namespace Rapture\Template\Adapter;

use Rapture\Template\Definition\TemplateInterface;

/**
 * Rapture template engine
 *
 * @package Rapture\Template
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class Php implements TemplateInterface
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
        $tmpResource = tmpfile();
        $stream = stream_get_meta_data($tmpResource);
        $tmpFile = $stream['uri'];
        fwrite($tmpResource, $this->body);

        extract((array)$this->params);
        ob_start();
        include $tmpFile;
        $result = ob_get_contents();
        ob_end_clean();

        fclose($tmpResource);

        return $result;
    }
}
