<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $this->title ?></title>
    <? // TODO meta <meta name="description" content=""> ?>

    <? foreach ($this->styles['css'] as $style): ?>
    <link rel="stylesheet" type="text/css" href="<?= $style ?>"/>
    <? endforeach; ?>
    <? foreach ($this->styles['less'] as $style): ?>
    <link rel="stylesheet/less" type="text/css" href="<?= $style ?>"/>
    <? endforeach; ?>
    <? foreach ($this->scripts as $script): ?>
    <script type="text/javascript" src="<?= $script ?>"></script>
    <? endforeach; ?>
</head>
<body>
<? $this->layout->display() ?>
</body>
</html>