<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class Bord
{
    private $vakjes = [];

    public function __construct()
    {
        for ($XC = 0; $XC < 10; $XC++) {
            for ($YC = 0; $YC < 10; $YC++) {
                if ($XC % 2 === $YC % 2) {
                    $vakKleur = "zwart";
                    if ($YC > 5) {
                        $steenKleur = "zwart";
                    } elseif ($YC < 4) {
                        $steenKleur = "wit";
                    } else {
                        $steenKleur = null;
                    }
                } else {
                    $vakKleur = "wit";
                    $steenKleur = null;
                }
                $this->vakjes[$XC][$YC] = new Vak($vakKleur, $steenKleur);
            }
        }
    }

    public function voerZetUit($zet)
    {
        $vanPositie = $zet->getVanPositie();
        $naarPositie = $zet->getNaarPositie();
        $vanX = intval($vanPositie->getX());
        $vanY = intval($vanPositie->getY());
        $naarX = intval($naarPositie->getX());
        $naarY = intval($naarPositie->getY());
        $this->vakjes[$naarX][$naarY]->giveSteen($this->vakjes[$vanX][$vanY]->moveSteen());
        if ($this->vakjes[$naarX][$naarY]->getSteen()->getRichting() === "up" && $naarY === 9) {
            $this->vakjes[$naarX][$naarY]->getSteen()->switchRichting();
        }
        if ($this->vakjes[$naarX][$naarY]->getSteen()->getRichting() === "down" && $naarY === 0) {
            $this->vakjes[$naarX][$naarY]->getSteen()->switchRichting();
        }
    }

    public function voerSlagUit($zet)
    {
        $vanPositie = $zet->getVanPositie();
        $naarPositie = $zet->getNaarPositie();
        $vanX = intval($vanPositie->getX());
        $vanY = intval($vanPositie->getY());
        $naarX = intval($naarPositie->getX());
        $naarY = intval($naarPositie->getY());
        $this->vakjes[$naarX][$naarY]->giveSteen($this->vakjes[$vanX][$vanY]->moveSteen());
        $geslagenVakX = (($naarX - $vanX) / 2) + $vanX;
        $geslagenVakY = (($naarY - $vanY) / 2) + $vanY;
        $this->vakjes[$geslagenVakX][$geslagenVakY]->giveSteen(null);
        if ($this->vakjes[$naarX][$naarY]->getSteen()->getRichting() === "up" && $naarY === 9) {
            $this->vakjes[$naarX][$naarY]->getSteen()->switchRichting();
        }
        if ($this->vakjes[$naarX][$naarY]->getSteen()->getRichting() === "down" && $naarY === 0) {
            $this->vakjes[$naarX][$naarY]->getSteen()->switchRichting();
        }
    }

    public function printStatus()
    {
        echo " Y  Y  Y  Y  Y  Y  Y  Y  Y  Y" . PHP_EOL;
        echo " 0  1  2  3  4  5  6  7  8  9" . PHP_EOL;
        foreach ($this->vakjes as $XC => $row) {
            foreach ($row as $key => $vak) {
                $vakKleur = $vak->getKleur();
                $steen = $vak->getSteen();
                if ($steen !== null) {
                    $steenKleur = $steen->getKleur();
                } else {
                    $steenKleur = null;
                }
                switch (true) {
                    case $steenKleur == "zwart":
                        echo "[Z]";
                        break;

                    case $steenKleur == "wit":
                        echo "[W]";
                        break;

                    case $vakKleur == "zwart":
                        echo "[ ]";
                        break;

                    case $vakKleur == "wit":
                        echo "( )";
                        break;

                    default:
                        echo "? ";
                        break;
                }
            }
            echo " " . $XC . " X";
            echo PHP_EOL;
        }
    }

    public function getVakjes()
    {
        return $this->vakjes;
    }

    public function countStenen()
    {
        $wit = 0;
        $zwart = 0;
        foreach ($this->vakjes as $XC => $row) {
            foreach ($row as $YC => $vak) {
                $steen = $vak->getSteen();
                if ($steen !== null) {
                    if ($steen->getKleur() === "wit") {
                        $wit++;
                    } elseif ($steen->getKleur() === "zwart") {
                        $zwart++;
                    }
                }
            }
        }
        return ["wit" => $wit, "zwart" => $zwart];
    }
}
