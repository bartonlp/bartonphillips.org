<?php
    require_once '../vendor/autoload.php';
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

<?php
    require_once '../vendor/autoload.php';
    //Getting just some messages
    //instance
    $MailBox = EmailMD\MailBoxFactory::gmail(
        'yourusername@gmail.com',
        'yourpassword'
    );

    //Get some messages
    $limit = 10;
    foreach ( $MailBox as $messageNumber => $message ) {
        echo 'Message number: ' . $messageNumber . PHP_EOL;
        echo $message->getSubject() . PHP_EOL;
        $limit--;
        if ( $limit < 1 ) {
            break;
        }
    }
?>

<?php
    require_once '../vendor/autoload.php';
    //Getting messages recieved since a specific date
    //instance
    $MailBox = EmailMD\MailBoxFactory::gmail(
        'yourusername@gmail.com',
        'yourpassword'
    );

    //Since today
    $MailBox->filterSince(new DateTime());
    foreach ( $MailBox as $messageNumber => $message ) {
        echo 'Message number: ' . $messageNumber . PHP_EOL;
        echo $message->getSubject() . PHP_EOL;
    }
    //Since yesterday
    $MailBox->filterSince(new DateTime('-1 days'));
    foreach ( $MailBox as $messageNumber => $message ) {
        echo 'Message number: ' . $messageNumber . PHP_EOL;
        echo $message->getSubject() . PHP_EOL;
    }
?>

<?php
    require_once '../vendor/autoload.php';
    //Getting messages in reverse order
    //instance
    $MailBox = EmailMD\MailBoxFactory::gmail(
        'yourusername@gmail.com',
        'yourpassword'
    );
    $MailBox->reverse();//Now we get the newest first

    //Since today
    $MailBox->filterSince(new DateTime());
    foreach ( $MailBox as $messageNumber => $message ) {
        echo 'Message number: ' . $messageNumber . PHP_EOL;
        echo $message->getSubject() . PHP_EOL;
    }
    $MailBox->reverse();//Now we get the oldest first
?>