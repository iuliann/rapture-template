<?php

namespace Rapture\Template;

/**
 * Rapture template engine
 *
 * @package Rapture\Template
 * @author  Iulian N. <rapture@iuliann.ro>
 * @license LICENSE MIT
 */
class Template
{
    /** @var string */
    protected $name = '';

    /** @var array */
    protected $params = [];

    /** @var string */
    protected $extension = 'phtml';

    /** @var array */
    protected $paths = ['./views'];

    /** @var array */
    protected $inherited = [];

    /** @var array */
    protected $blocks = [];

    /** @var array */
    protected $filters = [];

    /**
     * Template constructor.
     *
     * @param string|null $name
     * @param array       $params
     */
    public function __construct($name = '', array $params = [])
    {
        $this->name   = $name;
        $this->params = $params;
    }

    /*
     * Setters
     */

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = trim((string)$name, '/');

        return $this;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return $this
     */
    public function setExtension($extension = 'phtml')
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Set base path for templates
     *
     * @param array $paths
     *
     * @return $this
     */
    public function setPaths($paths = ['./views'])
    {
        $paths = (array)$paths;
        foreach ($paths as $index => $path) {
            $paths[$index] = rtrim($path, '/');
        }
        $this->paths = array_unique($paths);

        return $this;
    }

    /**
     * Set params
     *
     * @param array $params Array with params
     *
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params + $this->params;

        return $this;
    }

    /*
     * Getters
     */

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get inherited parents
     *
     * @return array
     */
    public function getInherited()
    {
        return $this->inherited;
    }

    /*
     * Magic get of variable
     */

    /**
     * __get variables
     *
     * @param string $name Variable name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set(string $name, $value)
    {
        $this->params[$name] = $value;
    }

    /*
     * Inheritance
     */

    /**
     * Inherit a parent template
     *
     * @param string $name Template to inherit
     *
     * @return $this
     */
    public function inherit($name)
    {
        $this->inherited[] = $name;

        return $this;
    }

    /**
     * Remove inherited templates so far
     *
     * @return $this
     */
    public function removeInherited()
    {
        $this->inherited = [];

        return $this;
    }

    /*
     * Rendering
     */

    /**
     * Render the template
     *
     * @return string
     */
    public function render()
    {
        $this->inherited[] = $this->name;

        extract($this->params);
        $t = $this;

        ob_start();

        while (count($this->inherited)) {
            include $this->name(array_pop($this->inherited));
            $this->content = ob_get_contents();
            ob_clean();
        }

        ob_end_clean();

        return (string)$this->content;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Include a template
     *
     * @param string $name   Template name
     * @param array  $params Variables to use with included template
     *
     * @return string
     */
    public function insert($name, array $params = [])
    {
        return (new self($name, $params))
            ->setPaths($this->paths)
            ->setFilters($this->filters)
            ->setExtension($this->extension)
            ->render();
    }

    /**
     * Start a block of a template section
     *
     * @param string  $name  Block name
     *
     * @return void
     */
    public function open($name)
    {
        $this->blocks[] = $name;

        ob_start();
    }

    /**
     * End a block of template
     *
     * @return void
     */
    public function close()
    {
        if (count($this->blocks) == 0) {
            throw new \LogicException('No blocks started!');
        }

        $block = array_pop($this->blocks);

        $this->params[$block] = ob_get_clean();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function name(string $name):string
    {
        $name = trim($name, '/');

        foreach ($this->paths as $path) {
            if (is_readable("{$path}/{$name}.{$this->extension}")) {
                return "{$path}/{$name}.{$this->extension}";
            }
        }

        throw new \InvalidArgumentException("Template name not found: '{$name}'");
    }

    /*
     * Filters
     */

    /**
     * Escape string on output
     *
     * @param mixed  $value
     * @param string $filters
     *
     * @return string
     */
    public function e($value, $filters = '')
    {
        return $this->f(htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), $filters);
    }

    /**
     * Register filters
     * [filterName => callback]
     *
     * @param array  $filters  Array of filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters + $this->filters;

        return $this;
    }

    /**
     * Filter a value
     *
     * @param mixed  $value   Value to filter
     * @param string $filters String of filters separated by pipe "|"
     *
     * @return mixed
     */
    public function f($value, $filters)
    {
        $filters = explode('|', $filters);

        foreach ($filters as $filter) {
            if (isset($this->filters[$filter])) {
                if (is_callable($this->filters[$filter])) {
                    $value = $this->filters[$filter]($value);
                }
            }
            elseif (is_callable($filter)) {
                $value = $filter($value);
            }
        }

        return $value;
    }
}
