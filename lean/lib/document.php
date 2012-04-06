<?php
namespace lean;

/**
 * Representst the document
 * TODO do not extend template anymore and delegate?
 */
class Document extends Template {

    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var array
     */
    protected $scripts = array();
    /**
     * @var array
     */
    protected $styles = array();

    /**
     * @param string $file
     */
    public function __construct($file) {
        parent::__construct($file);
    }

    /**
     * @param string $style
     */
    public function addStyle($style) {
        $this->styles[] = $style;
    }

    /**
     * @param string $script
     */
    public function addScript($script) {
        $this->scripts[] = $script;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }
}