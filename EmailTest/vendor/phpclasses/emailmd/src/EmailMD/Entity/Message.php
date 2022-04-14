<?php
namespace EmailMD\Entity;
/**
 * Entity factory
 *
 * @package EmailMD.Entity
 */
class Message
{
    /**
     * the message subject
     *
     * @var string
     */
    private $_subject;

    /**
     * who sent it
     *
     * @var string
     */
    private $_from;

    /**
     * recipient
     *
     * @var string
     */
    private $_to;

    /**
     * when was it sent
     *
     * @var string
     */
    private $_date;

    /**
     * Message-ID
     *
     * @var string
     */
    private $_messageId;

    /**
     * is a reference to this message id
     *
     * @var string
     */
    private $_references;

    /**
     * is a reply to this message id
     *
     * @var string
     */
    private $_inReplyTo;

    /**
     * [$subject description]
     * @var string
     */
    private $_size;

    /**
     * UID the message has in the mailbox
     *
     * @var string
     */
    private $_uid;

    /**
     *  message sequence number in the mailbox
     *
     * @var string
     */
    private $_number;

    /**
     * flagged as recent?
     *
     * @var boolean
     */
    private $_recent;

    /**
     * flagged?
     *
     * @var boolean
     */
    private $_flagged;

    /**
     * flagged as answered?
     *
     * @var boolean
     */
    private $_answered;

    /**
     * flagged for deletion?
     *
     * @var boolean
     */
    private $_deleted;

    /**
     * flagged as already read?
     *
     * @var boolean
     */
    private $_seen;

    /**
     * flagged as already draft?
     *
     * @var boolean
     */
    private $_draft;

    /**
     * [$subject description]
     * @var string
     */
    private $_udate;

    /**
     * [$subject description]
     * @var string
     */
    private $_body;

    /**
     * Gets the the message subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Sets the the message subject.
     *
     * @param string $subject the subject
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->_subject = (string)$subject;

        return $this;
    }

    /**
     * Gets the who sent it.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * Sets the who sent it.
     *
     * @param string $from the from
     *
     * @return self
     */
    public function setFrom($from)
    {
        $this->_from = (string)$from;

        return $this;
    }

    /**
     * Gets the recipient.
     *
     * @return string
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * Sets the recipient.
     *
     * @param string $to the to
     *
     * @return self
     */
    public function setTo($to)
    {
        $this->_to = (string)$to;

        return $this;
    }

    /**
     * Gets the when was it sent.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Sets the when was it sent.
     *
     * @param string $date the date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->_date = (string)$date;

        return $this;
    }

    /**
     * Gets the Message-ID.
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->_messageId;
    }

    /**
     * Sets the Message-ID.
     *
     * @param string $messageId the Message-ID
     *
     * @return self
     */
    public function setMessageId($messageId)
    {
        $this->_messageId = (string)$messageId;

        return $this;
    }

    /**
     * Gets the is a reference to this message id.
     *
     * @return string
     */
    public function getReferences()
    {
        return $this->_references;
    }

    /**
     * Sets the is a reference to this message id.
     *
     * @param string $references the references
     *
     * @return self
     */
    public function setReferences($references)
    {
        $this->_references = (string)$references;

        return $this;
    }

    /**
     * Gets the is a reply to this message id.
     *
     * @return string
     */
    public function getInReplyTo()
    {
        return $this->_inReplyTo;
    }

    /**
     * Sets the is a reply to this message id.
     *
     * @param string $inReplyTo the in reply to
     *
     * @return self
     */
    public function setInReplyTo($inReplyTo)
    {
        $this->_inReplyTo = (string)$inReplyTo;

        return $this;
    }

    /**
     * Gets the [$subject description].
     *
     * @return string
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Sets the [$subject description].
     *
     * @param string $size the size
     *
     * @return self
     */
    public function setSize($size)
    {
        $this->_size = (string)$size;

        return $this;
    }

    /**
     * Gets the UID the message has in the mailbox.
     *
     * @return string
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * Sets the UID the message has in the mailbox.
     *
     * @param string $uid the uid
     *
     * @return self
     */
    public function setUid($uid)
    {
        $this->_uid = (string)$uid;

        return $this;
    }

    /**
     * Gets the message sequence number in the mailbox.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * Sets the message sequence number in the mailbox.
     *
     * @param string $number the number
     *
     * @return self
     */
    public function setNumber($number)
    {
        $this->_number = (string)$number;

        return $this;
    }

    /**
     * Gets the flagged as recent?.
     *
     * @return boolean
     */
    public function getRecent()
    {
        return $this->_recent;
    }

    /**
     * Sets the flagged as recent?.
     *
     * @param boolean $recent the recent
     *
     * @return self
     */
    public function setRecent($recent)
    {
        $this->_recent = (boolean)$recent;

        return $this;
    }

    /**
     * Gets the flagged?.
     *
     * @return boolean
     */
    public function getFlagged()
    {
        return $this->_flagged;
    }

    /**
     * Sets the flagged?.
     *
     * @param boolean $flagged the flagged
     *
     * @return self
     */
    public function setFlagged($flagged)
    {
        $this->_flagged = (boolean)$flagged;

        return $this;
    }

    /**
     * Gets the flagged as answered?.
     *
     * @return boolean
     */
    public function getAnswered()
    {
        return $this->_answered;
    }

    /**
     * Sets the flagged as answered?.
     *
     * @param boolean $answered the answered
     *
     * @return self
     */
    public function setAnswered($answered)
    {
        $this->_answered = (boolean)$answered;

        return $this;
    }

    /**
     * Gets the flagged for deletion?.
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->_deleted;
    }

    /**
     * Sets the flagged for deletion?.
     *
     * @param boolean $deleted the deleted
     *
     * @return self
     */
    public function setDeleted($deleted)
    {
        $this->_deleted = (boolean)$deleted;

        return $this;
    }

    /**
     * Gets the flagged as already read?.
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->_seen;
    }

    /**
     * Sets the flagged as already read?.
     *
     * @param boolean $seen the seen
     *
     * @return self
     */
    public function setSeen($seen)
    {
        $this->_seen = (boolean)$seen;

        return $this;
    }

    /**
     * Gets the flagged as already draft?.
     *
     * @return boolean
     */
    public function getDraft()
    {
        return $this->_draft;
    }

    /**
     * Sets the flagged as already draft?.
     *
     * @param boolean $draft the draft
     *
     * @return self
     */
    public function setDraft($draft)
    {
        $this->_draft = (boolean)$draft;

        return $this;
    }

    /**
     * Gets the [$subject description].
     *
     * @return string
     */
    public function getUdate()
    {
        return $this->_udate;
    }

    /**
     * Sets the [$subject description].
     *
     * @param string $udate the udate
     *
     * @return self
     */
    public function setUdate($udate)
    {
        $this->_udate = (string)$udate;

        return $this;
    }

    /**
     * Gets the [$subject description].
     *
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Sets the [$subject description].
     *
     * @param string $body the body
     *
     * @return self
     */
    public function setBody($body)
    {
        $this->_body = (string)$body;

        return $this;
    }
}