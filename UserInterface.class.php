<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class UserInterface
{
    public function vraagSpelerOmZet($spelerAanDeBeurt)
    {
        $spelerAanDeBeurt = ($spelerAanDeBeurt === 0) ? "wit" : "zwart";
        echo "Welke steen wil je versetten, Speler $spelerAanDeBeurt?" . PHP_EOL;
        $vanX = readline("X Cord: ");
        $vanY = readline("Y Cord: ");
        echo "Naar welk vak wil je deze steen verzetten, Speler $spelerAanDeBeurt??" . PHP_EOL;
        $naarX = readline("X Cord: ");
        $naarY = readline("Y Cord: ");
        return new Zet($vanX, $vanY, $naarX, $naarY);
    }

    public function vraagSpelerOmKeuzenUitGeforceerdeZetten($geforceerdeZetten)
    {
        while (true) {
            echo "Choose one of the shown items:" . PHP_EOL;
            foreach ($geforceerdeZetten as $key => $zet) {
                $vanPositie = $zet->getVanPositie();
                $naarPositie = $zet->getNaarPositie();
                $vanX = intval($vanPositie->getX());
                $vanY = intval($vanPositie->getY());
                $naarX = intval($naarPositie->getX());
                $naarY = intval($naarPositie->getY());
                echo "ID:$key | (X:$vanX-Y:$vanY => X:$naarX-Y:$naarY)" . PHP_EOL;
            }
            $spelerKeuzenUitGeforceerdeZetten = intval(readline("Choose ID of forced move: "));
            if ($spelerKeuzenUitGeforceerdeZetten >= 0 && $spelerKeuzenUitGeforceerdeZetten <= count($geforceerdeZetten) - 1) {
                return $geforceerdeZetten[$spelerKeuzenUitGeforceerdeZetten];
            } else {
                echo "Your choose doesn't exist. PLease try again." . PHP_EOL;
            }
        }
    }
}
