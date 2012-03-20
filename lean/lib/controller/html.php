<?php
namespace lean\controller;

/**
 * TODO DOCUMENTATION!
 */
abstract class HTML extends \lean\Controller {
    private $view;

    public function __construct(\lean\Application $application) {
        parent::__construct($application);
        $this->document = new \lean\Document($this->getDocumentFile());
        $this->layout = new \lean\Template($this->getLayoutFile());
        $this->view = $this->createView();
    }

    protected function getDocumentFile() {
        return LEAN_ROOT . '/templates/document.php';
    }
    protected abstract function getLayoutFile();
    protected function getViewDirectory() {
        return APPLICATION_ROOT . '/views';
    }

    protected function getDocument() {
        return $this->document;
    }
    protected function getLayout() {
        return $this->layout;
    }
    protected function getView() {
        return $this->view;
    }

    protected function createView() {
        $class = \lean\Text::offsetLeft(get_called_class(), $this->getApplication()->getControllerNamespace());
        $class = \lean\Text::offsetLeft($class, 1);
        $file = \strtolower($class);
        $file = \str_replace('\\', '/', $file);
        $file = $file . '.php';
        //TODO application setting
        $view = new \lean\Template(APPLICATION_ROOT . '/views/' . $file, $file);
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

        // will be null if not set
        if(!isset($document->title))
            $document->title = '';

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