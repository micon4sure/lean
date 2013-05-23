<?php
namespace lean;

class Wrapper {

    /**
     * @var string name of the wrapper, also used for the filename
     */
    private $name;
    /**
     * @var Application
     */
    private $application;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var Wrapper
     */
    private $wrapper;

    /**
     * @param $name
     * @param $application
     */
    public function __construct($name, $application) {
        $this->name = $name;
        $this->application = $application;
        $this->template = $this->createTemplate();
    }

    /**
     * Wrap this wrapper
     * @param Wrapper $wrapper
     */
    public function setWrapper(self $wrapper) {
        $this->wrapper = $wrapper;
    }

    /**
     * Wrap a template
     * @param Template $view
     * @return Template
     */
    public function wrap(Template $view) {
        $template = $this->getTemplate();
        $template->set('next', $view);

        if(!$this->wrapper) {
            return $template;
        }

        return $this->wrapper->wrap($template);
    }

    /**
     * @return Template
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Create the template instance
     * @return Template
     */
    public function createTemplate() {
        $template = new Template($this->getWrapperDirectory() . '/' . $this->name . '.php');
        $template->setCallback('urlFor', [$this->application, 'urlFor']);
        $template->setCallback('urlForDefault', [$this->application, 'urlForDefault']);
        return $template;
    }

    /**
     * Get the directory the wrapper templates are in
     * @return mixed
     */
    protected function getWrapperDirectory() {
        return $this->application->getSetting('lean.template.wrapper.directory');
    }

    /**
     * @return Application
     */
    protected function getApplication() {
        return $this->application;
    }
}