<?php

namespace SynchWeb\Pages;

use SynchWeb\Page;

class Notebook extends Page
{
    public static $arg_list = array('prop' => '\w\w\d+',
                        'visit' => '\w+\d+-\d+',
                        'nid' => '\d+',
                        'title' => '.*',
                        'content' => '.*',
                        'raw' => '.*',
                        'user' => '.*',
    );

    public static $dispatch = array(
                            array('(/:visit)(/:nid)', 'get', '_get_notebooks'),
                            array('(/:visit)', 'put', '_add_notebook'),
                            array('(/:visit)(/:nid)', 'post', '_update_notebook'),
                            array('(/:visit)(/:nid)', 'delete', '_delete_notebook')
    );

    /**
     * Get all notebooks as a list or a single notebook for editing
     */
    function _get_notebooks(){
        $sessionId = $this->_get_Session();

        $where = "sessionId = :1";
        $params = array($sessionId);

        if($this->has_arg('nid')){
            $where .= " AND notebookId = :".(sizeof($params)+1);
            array_push($params, $this->arg('nid'));
        }

        $notebooks = $this->db->pq("SELECT nb.notebookid, nb.title, nb.content, nb.created, p.login as createdby, nb.modified, pe.login as modifiedby 
                    FROM Notebook nb 
                    INNER JOIN Person p on nb.createdBy = p.personId
                    INNER JOIN Person pe on nb.modifiedBy = pe.personId
                    WHERE $where",
                    $params);

        return $this->_output($notebooks);
    }

    /**
     * Insert new Notebooks for a session/visit
     */
    function _add_notebook(){
        $sessionId = $this->_get_Session();
        $personId = $this->_get_User();
        $contentRaw = $this->_create_ContentRaw();

        $this->db->pq("INSERT INTO Notebook (notebookid, title, content, contentraw, createdby, modifiedby, sessionid) 
                    VALUES(s_notebook.nextval, :1, :2, :3, :4, :5, :6)", 
                    array($this->arg('title'), $this->arg('content'), $contentRaw, $personId, $personId, $sessionId));
        $nid = $this->db->id();
        
        return $this->_output("nid: ". $nid);
    }

    /**
     * Update existing notebook for a session/visit
     */
    function _update_notebook(){
        $sessionId = $this->_get_Session();
        $personId = $this->_get_User();

        $this->db->pq("UPDATE Notebook SET title = :1, content = :2, contentraw = :3, modifiedby = :4 
                    WHERE notebookid = :5 
                    AND sessionid = :6", 
                    array($this->arg('title'), $this->arg('content'), $this->_create_ContentRaw(), $personId, $this->arg('nid'), $sessionId));

        return $this->_output("nid: ".$this->arg('nid'));
    }

    /**
     * Delete notebook for a session/visit (some debate over whether this should be implemented)
     */
    function _delete_notebook(){
        if(!$this->has_arg('nid') || $this->arg('nid') == null) $this->_error('No notebook specified');
        $sessionId = $this->_get_Session();

        $this->db->pq("DELETE FROM Notebook WHERE notebookid = :1 AND sessionId = :2", 
                    array($this->arg('nid'), $sessionId));
        
        return $this->_output("nid: ". $this->arg('nid'));
    }

    /**
     * Helper method to get the sessionid from proposal and visit args
     */
    function _get_Session(){
        if (!$this->has_arg('prop')) $this->_error('No proposal specified');
        if (!$this->has_arg('visit')) $this->_error('No visit specified');

        $prop = $this->arg('prop');
        $propNumber = substr($prop, 2, strlen($prop));
        $propCode = substr($prop, 0, 2);
        $visit = $this->arg('visit');
        $vis = substr($visit, strripos($visit,'-')+1, sizeof($visit));

        $check = $this->db->pq("SELECT sessionid 
                            FROM blsession s
                            INNER JOIN proposal p ON s.proposalid = p.proposalid  
                            WHERE p.proposalnumber = :1
                            AND p.proposalcode = :2 
                            AND s.visit_number = :3", 
                            array($propNumber, $propCode, $vis));
        
        if(!sizeof($check)) $this->_error('No such session for this proposal');

        return $check[0]['SESSIONID'];
    }

    /**
     * Helper method to get the user who created/updated the Notebook
     */
    function _get_User(){
        if(!$this->has_arg('user')) $this->_error('No defined user');

        $user = $this->db->pq("SELECT personId FROM Person WHERE login = :1", array($this->arg('user')));

        if(!sizeof($user)) $this->_error('No such user');

        return $user[0]['PERSONID'];
    }

    /**
     * Helper method to strip html tags for contentRaw field (easier to text search)
     * Second argument can be added to strip_tags() to allow specific html tags to be included
     */
    function _create_ContentRaw(){
        if(!$this->has_arg('content')) $this->_error('No content provided');
        return strip_tags($this->arg('content'));
    }
}