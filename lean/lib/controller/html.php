<?php
namespace lean\controller;

/**
 * TODO DOCUMENTATION!
 */
abstract class HTML extends \lean\Controller {
    /**
     * @var \lean\Document;
     */
    private $document;
    /**
     * @var \lean\Template
     */
    private $layout;
    /**
     * @var \lean\Template
     */
    private $view;

    /**
     * Holds template variables
     * @var \lean\util\Object
     */
    protected $data;

    /**
     * @param \lean\Application $application
     */
    public function __construct(\lean\Application $application) {
        parent::__construct($application);
        $this->data = new \lean\util\Object();
    }

    /**
     * Create document, view and layout
     */
    public function init() {
        parent::init();

        $this->document = $this->createDocument();
        $this->layout = $this->createLayout();
        $this->view = $this->createView();
    }

    /**
     * Display hirarchy
     * controller
     *  -> document
     *      -> layout
     *          -> view
     */
    protected function display() {
        $document = $this->getDocument();
        $layout = $this->getLayout();
        $view = $this->getView();
        $view->setData($this->data->toArray());

        // stack
        $document->set('layout', $layout);
        $layout->set('view', $view);

        $document->display();
    }

    /**
     * @return \lean\Document
     */
    protected function createDocument() {
        $file = \lean\ROOT_PATH . '/templates/document.php';
        return new \lean\Document($file);
    }

    /**
     * @return \lean\Template
     */
    protected function createLayout() {
        $file = $this->getApplication()->getSetting('lean.templates.layouts.directory') . '/default.php';
        return new \lean\Template($file);
    }

    /**
     * TODO exploit testing
     * @return \lean\Template
     */
    protected function createView() {
        $chunks = explode("\\", get_class($this));
        $class = end($chunks);
        $file = \strtolower($class);
        $file = \str_replace('\\', '/', $file);
        $action = \lean\Text::splitCamelCase($this->getAction());
        $file = $this->getApplication()->getSetting('lean.templates.views.directory') . "/$file/$action.php";
        $view = new \lean\Template($file);
        return $view;
    }

    /**
     * @param \lean\Partial $partial
     */
    protected function addPartial(\lean\Partial $partial) {
        $this->getView()->setCallback($partial->getName(), $partial);
    }

    /**
     * @return \lean\Document
     */
    protected function getDocument() {
        return $this->document;
    }
    /**
     * @return \lean\Template
     */
    protected function getLayout() {
        return $this->layout;
    }
    /**
     * @return \lean\Template
     */
    protected function getView() {
        return $this->view;
    }
}