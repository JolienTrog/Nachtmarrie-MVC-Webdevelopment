<?php

namespace Nachtmerrie;

class View
{
    /** @var string */
    protected $outerLayout;

    /** @var string */
    protected $innerLayout;

    /** @var string */
    protected $title;

    /** @var array */
    protected $data;
    /**
     * @var string path to stylesheet
     */

    protected $stylesheet;

    public function setOuterLayout(string $pathToOuterLayout): self
    {
        $this->outerLayout = '/var/www/nachtmerrie/view/' . $pathToOuterLayout;

        return $this;
    }

    public function setInnerLayout(string $pathToInnerLayout): self
    {
        $this->innerLayout = '/var/www/nachtmerrie/view/' . $pathToInnerLayout;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setData(string $key, $data): self
    {
        $this->data[$key] = $data;

        return $this;
    }

    protected function renderInnerLayout(): void
    {
        require_once $this->innerLayout;
    }

    public function render(): string
    {
        ob_start();
        require_once $this->outerLayout;

        return ob_get_clean();
    }

    public function setStylesheet(string $stylesheet) : self
    {
        $this->stylesheet = '/css/' . $stylesheet;
        return $this;
    }
}