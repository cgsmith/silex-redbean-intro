<?php
require_once __DIR__ . '/vendor/autoload.php';

use RedBean_Facade as R;

$app = new Silex\Application();
$app['debug'] = true;

R::setup('sqlite:' . __DIR__ . '/data/db.sqlite');

$app->get('/beer/{id}', function ($id) use ($app) {
    if (!isset($id)) {
        $app->abort(404,'ID must be set');
    }
    $beer = R::load('beer', $id);

    if (empty($beer->name)) {
        $app->abort(404,"I'm thirsty - but couldn't find beer");
    }

    return 'Mmmmmm... ' . $beer->name;
})
->assert('id','\d+');

$app->post('/beer/{name}', function ($name) use ($app) {
    $beer = R::dispense('beer');
    $beer->name = $name;
    $id = R::store($beer);

    return 'Hey! I stored your beer name under ID ' . $id;
});


$app->run();