<?php

spl_autoload_register(function ($className) {
    require("$className.class.php");
});

class DamSpel
{
    private $bord;
    # spelerAanDeBeurt 0 = wit | spelerAanDeBeurt 1 = zwart
    private $spelerAanDeBeurt = 0;
    private $regelControleur;
    private $userInterface;

    public function __construct()
    {
        $this->bord = new Bord();
        $this->regelControleur = new RegelControleur();
        $this->userInterface = new UserInterface();
    }

    public function start()
    {
        echo "Welcome to Terminal Dammen. Here are some of the rules;" . PHP_EOL;
        echo "1. Both sides can move the pawns of there side." . PHP_EOL;
        echo "2. Pawns can only move 1 place, unless they slay an pawn of the opponent." . PHP_EOL;
        echo "3. You can only slay on pawn when the opistite colomn has no pawn on it." . PHP_EOL;
        echo "3. If you are able to slay an pawn, you have to ♫ until you can't no more ♫." . PHP_EOL;
        echo "4. If you hit the opisite side, you wil move backwards." . PHP_EOL;
        do {
            $this->round();
            $stenen = $this->bord->countStenen();
        } while ($stenen["wit"] > 0 && $stenen["zwart"] > 0);
        $this->displayWinnaar();
    }

    private function round()
    {
        $geforceerdeZetten = $this->regelControleur->isZetGeforceerd($this->bord, $this->spelerAanDeBeurt);
        if ($geforceerdeZetten === false) {
            $this->bord->printStatus();
            $zet = $this->userInterface->vraagSpelerOmZet($this->spelerAanDeBeurt);
            $geldigeZet = $this->regelControleur->isGeldigeZet($zet, $this->bord, $this->spelerAanDeBeurt);
            if ($geldigeZet === true) {
                $this->bord->voerZetUit($zet);
                $this->playerSwitch();
            }
        } else {
            while ($geforceerdeZetten !== false) {
                $this->bord->printStatus();
                $zet = $this->userInterface->vraagSpelerOmKeuzenUitGeforceerdeZetten($geforceerdeZetten);
                $this->bord->voerSlagUit($zet);
                $geforceerdeZetten = $this->regelControleur->isZetGeforceerd($this->bord, $this->spelerAanDeBeurt);
            }
            $this->playerSwitch();
        }
    }

    private function playerSwitch()
    {
        if ($this->spelerAanDeBeurt === 0) {
            $this->spelerAanDeBeurt = 1;
        } else {
            $this->spelerAanDeBeurt = 0;
        }
    }

    private function displayWinnaar()
    {
        $this->bord->printStatus();
        $stenen = $this->bord->countStenen();
        echo "Match finished." . PHP_EOL;
        if ($stenen["wit"] === 0) {
            echo "Zwart has won the match." . PHP_EOL;
        } elseif ($stenen["zwart"] === 0) {
            echo "Wit has won the match." . PHP_EOL;
        }
        echo "Congrats. *Clap *Clap *Clap" . PHP_EOL;
    }
}
