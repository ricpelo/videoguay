<?php

/**
 * @tests/unit/templates/fixtures/usuarios.php
 *
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$nombre = $faker->unique()->userName;

return [
    'nombre'   => $nombre,
    'password' => Yii::$app->security->generatePasswordHash($nombre),
    'auth_key' => Yii::$app->security->generateRandomString(),
    'email'    => $nombre . '@' . $faker->safeEmailDomain,
];
