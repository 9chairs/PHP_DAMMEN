<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class Steen
{
    private $kleur;
    private $richting;

    public function __construct($kleur)
    {
        if ($kleur === "wit") {
            $this->richting = "up";
        }
        if ($kleur === "zwart") {
            $this->richting = "down";
        }
        $this->kleur = $kleur;
    }

    public function getKleur()
    {
        return $this->kleur;
    }

    public function getRichting()
    {
        return $this->richting;
    }

    public function switchRichting()
    {
        if ($this->richting === "up") {
            $this->richting = "down";
        } else {
            $this->richting = "up";
        }
    }
}
