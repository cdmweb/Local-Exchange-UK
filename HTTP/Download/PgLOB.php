<?php
// +----------------------------------------------------------------------+
// | PEAR :: HTTP :: Download :: PgLOB                                    |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is available at http://www.php.net/license/3_0.txt              |
// | If you did not receive a copy of the PHP license and are unable      |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2005 Michael Wallner <mike@iworks.at>             |
// +----------------------------------------------------------------------+
//
// $Id: PgLOB.php,v 1.12 2005/02/08 13:23:09 mike Exp $

/**
 * PgSQL large object stream interface for HTTP_Download
 * 
 * @author      Michael Wallner <mike@php.net>
 * @package     HTTP_Download
 * @category    HTTP
 * @license     PHP License
 */

$GLOBALS['_HTTP_Download_PgLOB_Connection'] = null;
stream_register_wrapper('pglob', 'HTTP_Download_PgLOB');

/**
 * PgSQL large object stream interface for HTTP_Download
 * 
 * Usage:
 * <code>
 * require_once 'HTTP/Download.php';
 * require_once 'HTTP/Download/PgLOB.php';
 * $db = &DB::connect('pgsql://user:pass@host/db');
 * // or $db = pg_connect(...);
 * $lo = HTTP_Download_PgLOB::open($db, 12345);
 * $dl = &new HTTP_Download;
 * $dl->setResource($lo);
 * $dl->send()
 * </code>
 * 
 * @access  public
 * @version $Revision: 1.12 $
 */
class HTTP_Download_PgLOB
{
    /**
     * Set Connection
     * 
     * @static
     * @access  public
     * @return  bool
     * @param   mixed   $conn
     */
    function setConnection($conn)
    {
        if (is_a($conn, 'DB_Common')) {
            $conn = $conn->dbh;
        } elseif (  is_a($conn, 'MDB_Common') || 
                    is_a($conn, 'MDB2_Driver_Common')) {
            $conn = $conn->connection;
        }
        if ($isResource = is_resource($conn)) {
            $GLOBALS['_HTTP_Download_PgLOB_Connection'] = $conn;
        }
        return $isResource;
    }
    
    /**
     * Get Connection
     * 
     * @static
     * @access  public
     * @return  resource
     */
    function getConnection()
    {
        if (is_resource($GLOBALS['_HTTP_Download_PgLOB_Connection'])) {
            return $GLOBALS['_HTTP_Download_PgLOB_Connection'];
        }
        return null;
    }
    
    /**
     * Open
     * 
     * @static
     * @access  public
     * @return  resource
     * @param   mixed   $conn
     * @param   int     $loid
     * @param   string  $mode
     */
    function open($conn, $loid, $mode = 'rb')
    {
        HTTP_Download_PgLOB::setConnection($conn);
        return fopen('pglob:///'. $loid, $mode);
    }
    
    /**#@+
     * Stream Interface Implementation
     * @internal
     */
    var $ID = 0;
    var $size = 0;
    var $conn = null;
    var $handle = null;
    
    function stream_open($path, $mode)
    {
        if (!$this->conn = HTTP_Download_PgLOB::getConnection()) {
            return false;
        }
        if (!preg_match('/(\d+)/', $path, $matches)) {
            return false;
        }
        $this->ID = $matches[1];
        
        if (!pg_query($this->conn, 'BEGIN')) {
            return false;
        }
        
        $this->handle = pg_lo_open($this->conn, $this->ID, $mode);
        if (!is_resource($this->handle)) {
            return false;
        }
        
        // fetch size of lob
        pg_lo_seek($this->handle, 0, PGSQL_SEEK_END);
        $this->size = (int) pg_lo_tell($this->handle);
        pg_lo_seek($this->handle, 0, PGSQL_SEEK_SET);
        
        return true;
    }
    
    function stream_read($length)
    {
        return pg_lo_read($this->handle, $length);
    }
    
    function stream_seek($offset, $whence = SEEK_SET)
    {
        return pg_lo_seek($this->handle, $offset, $whence);
    }
    
    function stream_tell()
    {
        return pg_lo_tell($this->handle);
    }
    
    function stream_eof()
    {
        return pg_lo_tell($this->handle) >= $this->size;
    }
    
    function stream_flush()
    {
        return true;
    }
    
    function stream_stat()
    {
        return array('size' => $this->size, 'ino' => $this->ID);
    }
    
    function stream_write($data)
    {
        return pg_lo_write($this->handle, $data);
    }
    
    function stream_close()
    {
        if (pg_lo_close($this->handle)) {
            return pg_query($this->conn, 'COMMIT');
        } else {
            pg_query($this->conn ,'ROLLBACK');
            return false;
        }
    }
    /**#@-*/
}

?>
