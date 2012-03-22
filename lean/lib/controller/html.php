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

    public function __construct(\lean\Application $application) {
        parent::__construct($application);
    }

    public function init() {
        parent::init();

        $this->document = new \lean\Document($this->getDocumentFile());
        $this->layout = new \lean\Template($this->getLayoutFile());
        $this->view = $this->createView();
    }

    protected function getDocumentFile() {
        return LEAN_ROOT . '/templates/document.php';
    }
    protected abstract function getLayoutFile();
    protected function getViewDirectory() {
        return $this->getApplication()->getSetting('lean.view.directory');
    }
    /**
     * @return \lean\Document
     */
    public function getDocument() {
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

    protected function createView() {
        $class = \lean\Text::offsetLeft(get_called_class(), $this->getApplication()->getControllerNamespace());
        $class = \lean\Text::offsetLeft($class, 1);
        $file = \strtolower($class);
        $file = \str_replace('\\', '/', $file);
        $action = $this->getAction();
        $file = $this->getViewDirectory() . "/$file/$action.php";
        //TODO application setting
        $view = new \lean\Template($file);
        return $view;
    }

    protected function display() {
        $document = $this->getDocument();
        $layout = $this->getLayout();
        $view = $this->getView();

        // stack
        $document->set('layout', $layout);
        $layout->set('view', $view);

        $view->setData($this->getView()->getData());

        $document->display();
    }

    protected function set($key, $val) {
        $this->getView()->set($key, $val);
        return $this;
    }
    protected function get($key) {
        return $this->getView()->get($key);
    }
}