<?php
namespace EmailMD;
/**
 * MailBox factory, make a mailbox, using gmail, yahoo, live or any other server you want
 *
 * @package EmailMD
 */
class MailBoxFactory
{
    /**
     * Make an instance of MailBox
     *
     * @param string $mailbox  Imap mailbox server full string
     * @param string $username Imap username
     * @param string $password Imap password
     *
     * @access public
     * @return EmailMD\MailBox
     */
    public static function make($mailbox, $username, $password)
    {
        $MailBox = new MailBox(
            new ImapStream($mailbox, $username, $password),
            new EntityFactory
        );
        return $MailBox;
    }

    /**
     * Make an instance of MailBox, using gmail
     *
     * @param string $username    Imap username
     * @param string $password    Imap password
     * @param string $mailboxName Imap mailbox name eg: INBOX, Trash, Drafts etc
     *
     * @access public
     * @return EmailMD\MailBox
     */
    public static function gmail($username, $password, $mailboxName = 'INBOX')
    {
        return static::make(
            '{imap.gmail.com:993/imap/ssl}' . $mailboxName,
            $username,
            $password
        );
    }

    /**
     * Make an instance of MailBox, using yahoo
     *
     * @param string $username    Imap username
     * @param string $password    Imap password
     * @param string $mailboxName Imap mailbox name eg: INBOX, Trash, Drafts etc
     *
     * @access public
     * @return EmailMD\MailBox
     */
    public static function yahoo($username, $password, $mailboxName = 'INBOX')
    {
        return static::make(
            '{imap.mail.yahoo.com:993/imap/ssl}' . $mailboxName,
            $username,
            $password
        );
    }

    /**
     * Make an instance of MailBox, using live
     *
     * @param string $username    Imap username
     * @param string $password    Imap password
     * @param string $mailboxName Imap mailbox name eg: INBOX, Trash, Drafts etc
     *
     * @access public
     * @return EmailMD\MailBox
     */
    public static function live($username, $password, $mailboxName = 'INBOX')
    {
        return static::make(
            '{imap-mail.outlook.com/imap/ssl}' . $mailboxName,
            $username,
            $password
        );
    }
}
