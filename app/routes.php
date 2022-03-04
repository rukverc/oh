<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->get('/check', App\Action\CheckAction::class)
->setName('check');

$app->get('/checkPost/{student}', App\Action\CheckPostAction::class)
->setName('checkPost');

