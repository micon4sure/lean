<?php
namespace lean\controller;

/**
 * TODO DOCUMENTATION!
 */
use lean\Template;
use lean\Wrapper;
use vitamin\util\Dump;

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

    /** @var Wrapper */
    private $wrapper;

    /**
     * Holds template variables
     *
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

        // register urlFor callback to view, layout and document
        $this->getDocument()->setCallback('urlFor', array($this, 'urlFor'));
        $this->getDocument()->setCallback('urlForDefault', array($this, 'urlForDefault'));
        $this->getLayout()->setCallback('urlFor', array($this, 'urlFor'));
        $this->getLayout()->setCallback('urlForDefault', array($this, 'urlForDefault'));
        $this->getView()->setCallback('urlFor', array($this, 'urlFor'));
        $this->getView()->setCallback('urlForDefault', array($this, 'urlForDefault'));
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
        $layout->set('view', $this->wrap($view));

        $document->display();
    }

    /**
     * Display the view
     */
    protected function displayView() {
        $view = $this->getView();
        $view->setData($this->data->toArray());
        $view->display();
    }

    /**
     * @return \lean\Document
     */
    protected function createDocument() {
        $file = \lean\ROOT_PATH . '/template/document.php';
        return new \lean\Document($file);
    }

    /**
     * get layout directory
     * @return string
     */
    protected function getLayoutDirectory() {
        return $this->getApplication()->getSetting('lean.template.layout.directory');
    }

    /**
     * @return \lean\Template
     */
    protected function createLayout() {
        $file = $this->getLayoutDirectory() . '/default.php';
        return new \lean\Template($file);
    }

    /**
     * get view directory
     * @return string
     */
    protected function getViewDirectory() {
        $path = $this->getApplication()->getSetting('lean.template.view.directory');
        return $path . '/' . implode('/', $this->getOrigin());
    }


    /**
     * @return \lean\Template
     */
    protected function createView() {
        $action = \lean\Text::splitCamelCase($this->getAction());
        $file = $this->getViewDirectory() . "/$action.php";
        $view = new \lean\Template($file);

        return $view;
    }

    /**
     * @param \lean\Partial $partial
     */
    protected function addPartial($name, \lean\Partial $partial) {
        $this->getView()->setCallback($name, $partial);
        $partial->init();
    }

    protected function wrap(Template $view) {
        if(!$this->wrapper) {
            return $view;
        }
        return $this->wrapper->wrap($view);
    }

    /**
     * @param Wrapper $wrapper
     */
    public function setWrapper(Wrapper $wrapper) {
        $this->wrapper = $wrapper;
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
}