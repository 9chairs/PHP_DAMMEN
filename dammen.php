<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

$damSpel = new DamSpel();

$damSpel->start();
