<?php
namespace EmailMD;
use EmailMD\Entity\Message;
/**
 * Entity factory
 *
 * @package EmailMD
 */
class EntityFactory
{
    private $_map = array(
        'subject' => 'setSubject',
        'from' => 'setFrom',
        'to' => 'setTo',
        'date' => 'setDate',
        'message_id' => 'setMessageId',
        'references' => 'setReferences',
        'in_reply_to' => 'setInReplyTo',
        'size' => 'setSize',
        'uid' => 'setUid',
        'msgno' => 'setNumber',
        'recent' => 'setRecent',
        'flagged' => 'setFlagged',
        'answered' => 'setAnswered',
        'deleted' => 'setDeleted',
        'seen' => 'setSeen',
        'draft' => 'setDraft',
        'udate' => 'setUdate'
    );

    /**
     * Make an instance of EmailMD\Entity\Message
     *
     * @param object $overview overview of the information in the headers of the given message
     * @param string $body     Message body
     *
     * @access public
     * @return EmailMD\Entity\Message
     */
    public function make($overview, $body)
    {
        $message = new Message;
        foreach ( $overview as $property => $value ) {
            if ( isset($this->_map[$property]) ) {
                $message->{$this->_map[$property]}($this->_decode($value));
            }
        }
        $message->setBody($this->_decode($body));
        return $message;
    }

    /**
     * Decode a message value, first imap_utf8 the quoted_printable_decode
     *
     * @param string $value A value to decode
     *
     * @access public
     * @return string
     */
    private function _decode($value)
    {
        return (string)quoted_printable_decode(imap_utf8($value));
    }
}
