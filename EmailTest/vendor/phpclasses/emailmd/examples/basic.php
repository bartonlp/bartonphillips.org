<?php
    require_once 'vendor/autoload.php';
    //Gmail
    $MailBox = EmailMD\MailBoxFactory::gmail(
        'yourusername@gmail.com',
        'yourpassword'
    );

    $MailBox->reverse();//Newest message first
    $MailBox->filterSince(new DateTime());//Just message recieved today
    //Get messages
    foreach ( $MailBox as $messageNumber => $message ) {
        echo 'Message number: ' . $messageNumber . PHP_EOL;
        echo $message->getSubject() . PHP_EOL;
    }
?>