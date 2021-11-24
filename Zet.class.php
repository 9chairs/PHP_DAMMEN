<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class Zet
{
    private $vanPositie;
    private $naarPositie;

    public function __construct($vanX, $vanY, $naarX, $naarY)
    {
        $this->vanPositie = new Positie($vanX, $vanY);
        $this->naarPositie = new Positie($naarX, $naarY);
    }

    public function getVanPositie()
    {
        return $this->vanPositie;
    }

    public function getNaarPositie()
    {
        return $this->naarPositie;
    }
}
