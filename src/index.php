<?php

require __DIR__.'/../vendor/autoload.php';

use Darkindy\Autroll\AutrollRunner;

if(!session_id()) {
    session_start();
}

$autrollRunner = new AutrollRunner;
$me = $autrollRunner->loginToFacebook();
if (!$me) { exit; }
$autrollRunner->getFriends();
//$autrollRunner->troll();

?>
