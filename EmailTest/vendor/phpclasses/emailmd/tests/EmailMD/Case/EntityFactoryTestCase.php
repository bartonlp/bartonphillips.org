<?php
namespace EmailMD\Test;
use EmailMD\Entity\Message;
use EmailMD\EntityFactory;

/**
 * Test EntityFactory
 *
 * @package EmailMD.Test.Case
 */
class EntityFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test make, passing all fields known
     *
     * @access public
     * @return null
     */
    public function testMakeHasAllKnownFields()
    {
        $overview = new \StdClass;
        $overview->subject = '=?ISO-8859-1?Q?Ultima_chamada_para_o_LiquidaShow!?=';
        $overview->from = 'Google <no-reply@accounts.google.com>';
        $overview->to = '=?ISO-8859-1?Q?Destinatarios_de_e-mail_de_resumo?=';
        $overview->date = 'Sun, 10 Sep 2014 21:09:35 +0000 (UTC)';
        $overview->message_id = '<adf24adf23432423Q@site.com>';
        $overview->references = 'yyyyyy@test';
        $overview->in_reply_to = 'xxxxx@test';
        $overview->size = '15250';
        $overview->uid = '111111';
        $overview->msgno = '22222';
        $overview->recent = '0';
        $overview->flagged = '1';
        $overview->answered = '0';
        $overview->deleted = '0';
        $overview->seen = '1';
        $overview->draft = '0';
        $overview->udate = '1410728740';
        $body = '=?ISO-8859-1?Q?Economize_de_verdade:_Voce_diz_o_quanto_que?= =?ISO-8859-1?Q?r_pagar_e_a_gente_negocia_o_preco_com_voce!?=';

        $Factory = new EntityFactory;

        $expected = new Message;
        $expected->setSubject('Ultima chamada para o LiquidaShow!');
        $expected->setFrom('Google <no-reply@accounts.google.com>');
        $expected->setTo('Destinatarios de e-mail de resumo');
        $expected->setDate('Sun, 10 Sep 2014 21:09:35 +0000 (UTC)');
        $expected->setMessageId('<adf24adf23432423Q@site.com>');
        $expected->setReferences('yyyyyy@test');
        $expected->setInReplyTo('xxxxx@test');
        $expected->setSize('15250');
        $expected->setUid('111111');
        $expected->setNumber('22222');
        $expected->setRecent('0');
        $expected->setFlagged('1');
        $expected->setAnswered('0');
        $expected->setDeleted('0');
        $expected->setSeen('1');
        $expected->setDraft('0');
        $expected->setUdate('1410728740');
        $expected->setBody('Economize de verdade: Voce diz o quanto quer pagar e a gente negocia o preco com voce!');

        $result = $Factory->make($overview, $body);
        $this->assertInstanceOf('EmailMD\Entity\Message', $result);

        $this->assertEquals($result, $expected);

        $overview2 = clone $overview;
        $overview2->recent = '1';
        $overview2->flagged = '0';
        $overview2->answered = '1';
        $overview2->deleted = '1';
        $overview2->seen = '0';
        $overview2->draft = '1';

        $expected->setRecent('1');
        $expected->setFlagged('0');
        $expected->setAnswered('1');
        $expected->setDeleted('1');
        $expected->setSeen('0');
        $expected->setDraft('1');

        $result = $Factory->make($overview2, $body);
        $this->assertInstanceOf('EmailMD\Entity\Message', $result);
        $this->assertEquals($result, $expected);
    }

    /**
     * Test make, passing some fields known
     *
     * @access public
     * @return null
     */
    public function testMakeHasSomeKnownFields()
    {
        $Factory = new EntityFactory;

        $overview = new \StdClass;
        $overview->subject = '=?ISO-8859-1?Q?Ultima_chamada_para_o_LiquidaShow!?=';
        $overview->from = 'Google <no-reply@accounts.google.com>';
        $overview->to = '=?ISO-8859-1?Q?Destinatarios_de_e-mail_de_resumo?=';
        $overview->deleted = '0';
        $overview->seen = '1';
        $overview->draft = '0';
        $overview->udate = '1410728740';
        $body = 'New Proin ut quam eros. Donec sed lobortis diam.\n Nulla nec odio lacus.';

        $expected = new Message;
        $expected->setSubject('Ultima chamada para o LiquidaShow!');
        $expected->setFrom('Google <no-reply@accounts.google.com>');
        $expected->setTo('Destinatarios de e-mail de resumo');
        $expected->setDeleted('0');
        $expected->setSeen('1');
        $expected->setDraft('0');
        $expected->setUdate('1410728740');
        $expected->setBody('New Proin ut quam eros. Donec sed lobortis diam.\n Nulla nec odio lacus.');

        $result = $Factory->make($overview, $body);
        $this->assertInstanceOf('EmailMD\Entity\Message', $result);
        $this->assertEquals($result, $expected);
    }
}