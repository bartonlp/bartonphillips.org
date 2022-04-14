EmailMD
=======

A PHP library to read e-mails.

Install with composer
------------
    {
        "require": {
            "rochasmarcelo/emailmd": "dev-master"
        }
    }

## Requirements ##

* PHP 5.3.*
* Imap extension

Example
------------
**Getting a MailBox instance**
```
<?php
    require_once 'vendor/autoload.php';
    //Gmail
    $MailBox = EmailMD\MailBoxFactory::gmail(
        'yourusername@gmail.com',
        'yourpassword'
    );

    //Hotmail / live / msn
    $MailBox = EmailMD\MailBoxFactory::live(
        'yourusername@live.com',
        'yourpassword'
    );

    //Yahoo
    $MailBox = EmailMD\MailBoxFactory::yahoo(
        'yourusername@yahoo.com',
        'yourpassword'
    );

    //Other pop3
    $MailBox = EmailMD\MailBoxFactory::make(
        '{localhost:110/pop3}INBOX',
        'yourusername@site.com',
        'yourpassword'
    );

    //Other imap
    $MailBox = EmailMD\MailBoxFactory::make(
        '{localhost:993/imap/ssl}INBOX',
        'yourusername@site.com',
        'yourpassword'
    );
?>
```

**Basic usage**

```
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
```
**Getting just some messages**

```
<?php
    require_once 'vendor/autoload.php';
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
```

**Getting messages recieved since a specific date**

```
<?php
    require_once 'vendor/autoload.php';
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
```

**Getting messages in reverse order**

By default the mailbox will return the oldest messages first. But sometimes we need to get the newest messages first, to do so we need to call the "MailBox::reverse" method one time.

```
<?php
    require_once 'vendor/autoload.php';
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
```
