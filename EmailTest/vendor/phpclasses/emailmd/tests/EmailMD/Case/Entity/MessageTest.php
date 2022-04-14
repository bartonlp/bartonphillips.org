<?php
namespace EmailMD\Test\Entity;

use EmailMD\Entity\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for set and gets
     *
     * @access public
     * @return null
     */
    public function testSetGetAll()
    {
        $array = array(
            'subject' => 'Integer elementum massa',
            'from' => 'Google <no-reply@accounts.google.com>',
            'to' => 'username@gmail.com',
            'date' => 'Sun, 10 Sep 2014 21:09:35 +0000 (UTC)',
            'message_id' => '<adf24adf23432423Q@site.com>',
            'references' => 'yyyyyy@test',
            'in_reply_to' => 'xxxxx@test',
            'size' => '15250',
            'uid' => '111111',
            'number' => '22222',
            'recent' => '0',
            'flagged' => '1',
            'answered' => '0',
            'deleted' => '0',
            'seen' => '1',
            'draft' => '0',
            'udate' => '1410728740',
            'body' => 'Proin ut quam eros. Donec sed lobortis diam. Nulla nec odio lacus. Quisque porttitor egestas dolor in placerat.'
        );

        $message = new Message;

        $message->setSubject($array['subject']);
        $expected = $array['subject'];
        $result = $message->getSubject();
        $this->assertSame($result, $expected);

        $message->setFrom($array['from']);
        $expected = $array['from'];
        $result = $message->getFrom();
        $this->assertSame($result, $expected);

        $message->setTo($array['to']);
        $expected = $array['to'];
        $result = $message->getTo();
        $this->assertSame($result, $expected);

        $message->setDate($array['date']);
        $expected = $array['date'];
        $result = $message->getDate();
        $this->assertSame($result, $expected);

        $message->setMessageId($array['message_id']);
        $expected = $array['message_id'];
        $result = $message->getMessageId();
        $this->assertSame($result, $expected);

        $message->setReferences($array['references']);
        $expected = $array['references'];
        $result = $message->getReferences();
        $this->assertSame($result, $expected);

        $message->setInReplyTo($array['in_reply_to']);
        $expected = $array['in_reply_to'];
        $result = $message->getInReplyTo();
        $this->assertSame($result, $expected);

        $message->setSize($array['size']);
        $expected = $array['size'];
        $result = $message->getSize();
        $this->assertSame($result, $expected);

        $message->setUid($array['uid']);
        $expected = $array['uid'];
        $result = $message->getUid();
        $this->assertSame($result, $expected);

        $message->setNumber($array['number']);
        $expected = $array['number'];
        $result = $message->getNumber();
        $this->assertSame($result, $expected);

        $message->setRecent($array['recent']);
        $expected = false;
        $result = $message->getRecent();
        $this->assertSame($result, $expected);

        $message->setRecent('1');
        $expected = true;
        $result = $message->getRecent();
        $this->assertSame($result, $expected);

        $message->setFlagged($array['flagged']);
        $expected = true;
        $result = $message->getFlagged();
        $this->assertSame($result, $expected);

        $message->setFlagged('0');
        $expected = false;
        $result = $message->getFlagged();
        $this->assertSame($result, $expected);

        $message->setAnswered($array['answered']);
        $expected = false;
        $result = $message->getAnswered();
        $this->assertSame($result, $expected);

        $message->setAnswered('1');
        $expected = true;
        $result = $message->getAnswered();
        $this->assertSame($result, $expected);

        $message->setDeleted($array['deleted']);
        $expected = false;
        $result = $message->getDeleted();
        $this->assertSame($result, $expected);

        $message->setDeleted('1');
        $expected = true;
        $result = $message->getDeleted();
        $this->assertSame($result, $expected);

        $message->setSeen($array['seen']);
        $expected = true;
        $result = $message->getSeen();
        $this->assertSame($result, $expected);

        $message->setSeen('0');
        $expected = false;
        $result = $message->getSeen();
        $this->assertSame($result, $expected);

        $message->setDraft($array['draft']);
        $expected = false;
        $result = $message->getDraft();
        $this->assertSame($result, $expected);

        $message->setDraft('1');
        $expected = true;
        $result = $message->getDraft();
        $this->assertSame($result, $expected);

        $message->setUdate($array['udate']);
        $expected = $array['udate'];
        $result = $message->getUdate();
        $this->assertSame($result, $expected);
    }
}