<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $this->title ?></title>
    <? // TODO meta <meta name="description" content=""> ?>

    <? foreach($this->styles as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>">
    <? endforeach; ?>
    <? foreach($this->scripts as $script): ?>
        <script src="$script"></script>
    <? endforeach; ?>
</head>
<body>
    <? $this->layout->display() ?>
</body>
</html>