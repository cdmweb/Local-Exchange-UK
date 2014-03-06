<?php
// $Id: composite.php,v 1.8 2002/12/02 05:23:00 jon Exp $
// $Horde: horde/lib/Log/composite.php,v 1.2 2000/06/28 21:36:13 jon Exp $

/**
 * The Log_composite:: class implements a Composite pattern which
 * allows multiple Log implementations to get sent the same events.
 *
 * @author  Chuck Hagenbuch <chuck@horde.org>
 * @version $Revision: 1.8 $
 * @since Horde 1.3
 * @package Log 
 */

class Log_composite extends Log {

    /** 
    * Array holding all Log instances 
    * which should be sent events sent to the composite. 
    * @var array
    */
    var $_children = array();


    /**
     * Constructs a new composite Log object.
     * 
     * @param boolean $name     This is ignored.
     * @param boolean $ident    This is ignored.
     * @param boolean $conf     This is ignored.
     * @param boolean $maxLevel This is ignored.
     * @access public
     */
    function Log_composite($name = false, $ident = false, $conf = false,
                           $maxLevel = PEAR_LOG_DEBUG)
    {
    }
    
    /**
     * Open the log connections of each and every child of this
     * composite.
     * @access public     
     */
    function open()
    {
        if (!$this->_opened) {
            reset($this->_children);
            foreach ($this->_children as $child) {
                $child->open();
            }
        }
    }

    /**
     * If we've gone ahead and opened each child, go through and close
     * each child.
     * @access public     
     */
    function close()
    {
        if ($this->_opened) {
            reset($this->_children);
            foreach ($this->_children as $child) {
                $child->close();
            }
        }
    }

    /**
     * Sends $message and $priority to every child of this composite.
     * 
     * @param string $message  The textual message to be logged.
     * @param string $priority (optional) The priority of the message. Valid
     *                  values are: PEAR_LOG_EMERG, PEAR_LOG_ALERT,
     *                  PEAR_LOG_CRIT, PEAR_LOG_ERR, PEAR_LOG_WARNING,
     *                  PEAR_LOG_NOTICE, PEAR_LOG_INFO, and PEAR_LOG_DEBUG.
     *                  The default is PEAR_LOG_INFO.
     * @return boolean  True on success or false on failure.
     */
    function log($message, $priority = PEAR_LOG_INFO)
    {
        reset($this->_children);
        foreach ($this->_children as $child) {
            $child->log($message, $priority);
        }
        
        $this->notifyAll(array('priority' => $priority, 'message' => $message));

        return true;
    }

    /**
     * @return boolean true if this is a composite class, false
     * otherwise. Always returns true since this is the composite
     * subclass.
     * @access public
     */
    function isComposite()
    {
        return true;
    }

    /**
     * Add a Log instance to the list of children that messages sent
     * to us should be passed on to.
     *
     * @param object Log &$child The Log instance to add.
     * @access public 
     * @return boolean false, if &$child isn't a Log instance    
     */
    function addChild(&$child)
    {
        if (!is_object($child)) {
            return false;
        }

        $child->_childID = uniqid(rand());

        $this->_children[$child->_childID] = &$child;
    }

    /**
     * Remove a Log instance from the list of children that messages
     * sent to us should be passed on to.
     *
     * @param object Log $child The Log instance to remove.
     * @access public     
     */
    function removeChild($child)
    {
        if (isset($this->_children[$child->_childID])) {
            unset($this->_children[$child->_childID]);
        }
    }
}

?>
