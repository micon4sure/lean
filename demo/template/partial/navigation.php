<ul>
    <? foreach($this->elements as $action => $title): ?>
    <li>
        <a href="<?= $this->urlFor(\lean\Application::DEFAULT_ROUTE_NAME, array('controller' => 'code', 'action' => $action)) ?>">
            <?= $title ?>
        </a>
    </li>
    <? endforeach; ?>
</ul>
