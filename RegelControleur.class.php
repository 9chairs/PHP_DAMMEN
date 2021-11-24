<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class RegelControleur
{
    public function isGeldigeZet($zet, $bord, $spelerAanDeBeurt)
    {
        $spelerAanDeBeurt = ($spelerAanDeBeurt === 0) ? "wit" : "zwart";
        $vanPositie = $zet->getVanPositie();
        $naarPositie = $zet->getNaarPositie();
        $vanX = intval($vanPositie->getX());
        $vanY = intval($vanPositie->getY());
        $naarX = intval($naarPositie->getX());
        $naarY = intval($naarPositie->getY());
        $vakjes = $bord->getVakjes();

        $vanSteen = $vakjes[$vanX][$vanY]->getSteen();
        $naarKleur = $vakjes[$naarX][$naarY]->getKleur();
        $naarSteen = $vakjes[$naarX][$naarY]->getSteen();
        if ($vanSteen !== null) {
            $vanSteenKleur = $vanSteen->getKleur();
            $vanSteenRichting = $vanSteen->getRichting();
        } else {
            $vanSteenKleur = null;
            $vanSteenRichting = null;
        }

        switch (true) {
            case $vanX < 0 && $vanX > 9 && $vanY < 0 && $vanY > 9:
                echo "'van' cordinaten zijn buiten het speelveld." . PHP_EOL;
                return false;
                break;

            case $naarX < 0 && $naarX > 9 && $naarY < 0 && $naarY > 9:
                echo "'naar' cordinaten zijn buiten het speelveld." . PHP_EOL;
                return false;
                break;

            case $vanSteen === null:
                echo "Geen steen gevonden op 'van' vak." . PHP_EOL;
                return false;
                break;

            case $vanSteenKleur !== $spelerAanDeBeurt:
                echo "Dit is de steen van je vijand." . PHP_EOL;
                return false;
                break;

            case $naarKleur !== "zwart":
                echo "Deze zit is naar een onjuiste vak. Je kan alleen op zwarte vakken staan." . PHP_EOL;
                return false;
                break;

            case abs($vanX - $naarX) > 1 && abs($vanY - $naarY) > 1:
                echo "Je kan maximaal 1 plaats vooruit kiezen." . PHP_EOL;
                return false;
                break;

            case $naarSteen !== null:
                echo "Op je zet staat al een steen." . PHP_EOL;
                return false;
                break;

            case $vanSteenRichting === "up" &&
                $naarSteen === null &&
                $spelerAanDeBeurt === $vanSteenKleur &&
                (($vanX + 1 === $naarX && $vanY + 1 === $naarY) || ($vanX - 1 === $naarX && $vanY + 1 === $naarY)):
                return true;
                break;

            case $vanSteenRichting === "down" &&
                $naarSteen === null &&
                $spelerAanDeBeurt === $vanSteenKleur &&
                (($vanX + 1 === $naarX && $vanY - 1 === $naarY) || ($vanX - 1 === $naarX && $vanY - 1 === $naarY)):
                return true;
                break;

            default:
                echo "Ongeldige actie. Overtreding onduidelijk" . PHP_EOL;
                return false;
                break;
        }
    }

    public function isZetGeforceerd($bord, $spelerAanDeBeurt)
    {
        $spelerAanDeBeurt = ($spelerAanDeBeurt === 0) ? "wit" : "zwart";
        $vakjes = $bord->getVakjes();
        $geforceerdeZettenTotaal = [];
        foreach ($vakjes as $XC => $row) {
            foreach ($row as $YC => $vak) {
                $steen = $vak->getSteen();
                if ($steen !== null) {
                    $geforceerdeZetten = $this->steenSlagen($XC, $YC, $steen, $vakjes, $spelerAanDeBeurt);
                    foreach ($geforceerdeZetten as $key => $zet) {
                        $geforceerdeZettenTotaal[] = $zet;
                    }
                }
            }
        }
        if (count($geforceerdeZettenTotaal) === 0) {
            return false;
        } else {
            return $geforceerdeZettenTotaal;
        }
    }

    private function steenSlagen($XC, $YC, $steen, $vakjes, $spelerAanDeBeurt)
    {
        $geforceerdeZetten = [];
        $steenKleur = $steen->getKleur();
        $steenRichting = $steen->getRichting();
        if ($steenRichting === "up" && $spelerAanDeBeurt === $steenKleur) {
            if ($YC + 2 >= 0 && $YC + 2 <= 9) {
                if ($XC + 2 >= 0 && $XC + 2 <= 9) {
                    if (
                        $vakjes[$XC + 1][$YC + 1]->getSteen() !== null &&
                        $vakjes[$XC + 2][$YC + 2]->getSteen() === null
                    ) {
                        if ($vakjes[$XC + 1][$YC + 1]->getSteen()->getKleur() !== $spelerAanDeBeurt) {
                            $geforceerdeZetten[] = new Zet($XC, $YC, $XC + 2, $YC + 2);
                        }
                    }
                }
                if ($XC - 2 >= 0 && $XC - 2 <= 9) {
                    if (
                        $vakjes[$XC - 1][$YC + 1]->getSteen() !== null &&
                        $vakjes[$XC - 2][$YC + 2]->getSteen() === null
                    ) {
                        if ($vakjes[$XC - 1][$YC + 1]->getSteen()->getKleur() !== $spelerAanDeBeurt) {
                            echo $vakjes[$XC - 1][$YC + 1]->getSteen()->getKleur() . " | " . $spelerAanDeBeurt;
                            $geforceerdeZetten[] = new Zet($XC, $YC, $XC - 2, $YC + 2);
                        }
                    }
                }
            }
        }
        if ($steenRichting === "down" && $spelerAanDeBeurt === $steenKleur) {
            if ($YC - 2 >= 0 && $YC - 2 <= 9) {
                if ($XC + 2 >= 0 && $XC + 2 <= 9) {
                    if (
                        $vakjes[$XC + 1][$YC - 1]->getSteen() !== null &&
                        $vakjes[$XC + 2][$YC - 2]->getSteen() === null
                    ) {
                        if ($vakjes[$XC + 1][$YC - 1]->getSteen()->getKleur() !== $spelerAanDeBeurt) {
                            $geforceerdeZetten[] = new Zet($XC, $YC, $XC + 2, $YC - 2);
                        }
                    }
                }
                if ($XC - 2 >= 0 && $XC - 2 <= 9) {
                    if (
                        $vakjes[$XC - 1][$YC - 1]->getSteen() !== null &&
                        $vakjes[$XC - 2][$YC - 2]->getSteen() === null
                    ) {
                        if ($vakjes[$XC - 1][$YC - 1]->getSteen()->getKleur() !== $spelerAanDeBeurt) {
                            $geforceerdeZetten[] = new Zet($XC, $YC, $XC - 2, $YC - 2);
                        }
                    }
                }
            }
        }
        return $geforceerdeZetten;
    }
}
