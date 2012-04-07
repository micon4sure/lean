<?php
namespace demo\partial;

class CodeNavigation extends \lean\Partial {
    public function display() {
        ?>
        <ul>
            <li>
                <a href="<?= $this->getApplication()->slim()->urlFor(\lean\Application::DEFAULT_ROUTE_NAME, array('controller' => 'code', 'action' => 'start')) ?>">
                    starting the engine
                </a>
            </li>
            <li>
                <a href="<?= $this->getApplication()->slim()->urlFor(\lean\Application::DEFAULT_ROUTE_NAME, array('controller' => 'code', 'action' => 'control')) ?>">
                    controlling the power
                </a>
            </li>
            <li>
                <a href="<?= $this->getApplication()->slim()->urlFor(\lean\Application::DEFAULT_ROUTE_NAME, array('controller' => 'code', 'action' => 'dump')) ?>">
                    taking a dump
                </a>
            </li>
        </ul>
        <?
    }
}