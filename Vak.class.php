<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class Vak
{
    private $kleur;
    private $steen = null;

    public function __construct($kleur, $steen)
    {
        $this->kleur = $kleur;
        if ($steen !== null) {
            $this->steen = new Steen($steen);
        }
    }

    public function getKleur()
    {
        return $this->kleur;
    }

    public function getSteen()
    {
        if ($this->steen !== null) {
            return $this->steen;
        } else {
            return null;
        }
    }

    public function moveSteen()
    {
        $steen = $this->steen;
        $this->steen = null;
        return $steen;
    }

    public function giveSteen($steen)
    {
        $this->steen = $steen;
    }
}
