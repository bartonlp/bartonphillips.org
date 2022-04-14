<?php
namespace EmailMD;
/**
 * Stream to an imap mail box
 *
 * @package EmailMD
 */
class ImapStream
{
    private $_resource;

    /**
     * imap username
     *
     * @var string
     */
    private $_username;

    /**
     * imap password
     *
     * @var string
     */
    private $_password;

    /**
     * imap mailbox. A mailbox name consists of a server and a mailbox path on this server. See
     * http://php.net/manual/pt_BR/function.imap-open.php
     *
     * @var string
     */
    private $_mailbox;

    /**
     * construct
     *
     * @param string $mailbox  Imap mailbox
     * @param string $username Imap username
     * @param string $password Imap password
     *
     * @access public
     * @throws InvalidArgumentException when mailbox, username or password is empty
     * @return void
     */
    public function __construct($mailbox, $username, $password)
    {
        if ( empty($mailbox) ) {
            throw new \InvalidArgumentException('The "mailbox" must not be empty');
        }

        if ( empty($username) ) {
            throw new \InvalidArgumentException('The "username" must not be empty');
        }

        if ( empty($password) ) {
            throw new \InvalidArgumentException('The "password" must not be empty');
        }

        $this->_mailbox = (string)$mailbox;
        $this->_username = (string)$username;
        $this->_password = (string)$password;
    }

    /**
     * Open the stream to the mailbox
     *
     * @access public
     * @throws UnexpectedValueException when could not connect to mailbox
     * @return resource
     */
    public function open()
    {
        if ( !$this->_resource ) {
            $this->_resource = imap_open($this->_mailbox, $this->_username, $this->_password);
        }

        if ( !$this->_resource ) {
            throw new \UnexpectedValueException('Could not connect to mailbox "' . $this->_mailbox . '".');

        }

        return $this->_resource;
    }

    /**
     * Close the stream to the mailbox
     *
     * @return boolean
     */
    public function close()
    {
        if ( $this->_resource ) {
          // BLP 2022-03-28 -
          //$this->_resource = null;
            return @imap_close($this->_resource);
        }

        $this->_resource = null;
        return true;
    }

    /**
     * Close an open the stream
     *
     * @return boolean
     */
    public function reopen()
    {
        $this->close();
        $this->open();
    }

    /**
     * destruct
     *
     * @access public
     * @return null
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Return the stream resource
     *
     * @return resource
     */
    public function getResource()
    {
        if ( !$this->_resource ) {
            $this->open();
        }
        return $this->_resource;
    }

    /**
     * method triggered when invoking inaccessible methods in an object context.
     *
     * @param string $name      method name inaccessible
     * @param array  $arguments arguments used
     *
     * @access public
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $name = 'imap_' . $name;
        if ( !function_exists($name) ) {
            throw new \BadMethodCallException("Call to undefined method ImapStream::{$name}().");
        }

        array_unshift($arguments, $this->getResource());
        return call_user_func_array($name, $arguments);
    }

}
