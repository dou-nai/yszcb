
<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>云书展</title>
    <meta name="keywords" content="云书展">
    <meta name="description" content="云书展" />

</head>

<body>

<div id="app">
    <app></app>
</div>


</body>

<script src="<?php echo e(mix('js/app.js','dist')); ?>"></script>
<?php /**PATH D:\yzsc\book_ex\Admin\resources\views/index.blade.php ENDPATH**/ ?>