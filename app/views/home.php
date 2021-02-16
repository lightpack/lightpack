<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>
        <link rel="icon" type="image/png" href="<?= url('assets/img/favicon-32x32.png') ?>" sizes="32x32" />
        <style>
            body {font-family: Verdana, Arial, 'sans-serif'; color: #214e7b}
            .container {display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;}
            h1 {text-shadow: 2px 2px #e2e2e2; font-weight: 400; font-size: 38px;}
            p {font-size: 14px;}
            img {max-width: 100%; width: 150px;}
        </style>
    </head>
    <body>
        <div class="container">
            <img class="logo" src="<?= url('assets/img/logo.png') ?>">
            <h1><?= $title ?></h1>
            <p><?= $message ?></p>
        </div>
    </body>
</html>