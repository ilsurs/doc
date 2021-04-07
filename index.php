<?php
// Query params are:  

require_once dirname(__FILE__) . '/kclient.php';
$client = new KClient('http://45.76.88.89/api.php?', '97jg5tvbbzhw92jdwrzzs1dsgrd8hyrj');
$client->sendAllParams();       // to send all params from page query
$client->forceRedirectOffer();       // redirect to offer if an offer is chosen
// $client->param('sub_id_5', '123'); // you can send any params
// $client->keyword('PASTE_KEYWORD');  // send custom keyword
// $client->currentPageAsReferrer(); // to send current page URL as click referrer
// $client->debug();              // to enable debug mode and show the errors
// $client->execute();             // request to api, show the output and continue
$client->executeAndBreak();     // to stop page execution if there is redirect or some output
?>
