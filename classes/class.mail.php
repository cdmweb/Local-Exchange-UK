<?php

class cMail extends cBasic {

	//CT new class - attempt to fix the sender for all mails in system
	private $from_name;
	private $from_address;
	//private $php_version; 
    private $to_name; 
    private $to_address; 
    private $subject; 
    private $message;
    //the final step before sending mail
    private $headers;
    private $formatted_subject; 
    private $formatted_message;
/*
// emails that need to use this class
anon - contact admin
member - member to member
admin - send to everyone (group->item)
system - weekly updates (group->item)
system - invoiced
system - weekly updates
system - reset password
system - site created
//NEW
member - contact admin (new)
anon - apply for membership (new)
system - invoice rejected (new)
system - new payment made to you (new)
system - new feedback to you (new)
system - core - new BAD feedback of member (new)

// nice to have
monthly community health (sent to member secretary) (group->item)
monthly economy (sent to treasurer) (group->item)
newsletter - new download available (group->item)

*/


	function __construct($variables=null){
		//can pass vars directly
		if(!empty($variables)) $this->Build($variables);
	}
	function Build($variables){
	    parent::Build($variables);
        //extra bits
        $this->setPhpVersion(phpversion());     
        $this->setHeaders($this->makeHeaders());     
        $this->setFormattedSubject($this->makeFormattedSubject());     
        $this->setFormattedMessage($this->makeFormattedMessage());     
	}


	function ProcessData() {
		global $cErr, $site_settings;
		if(mail($this->getToAddress(), $this->getFormattedSubject(), $this->getFormattedMessage(), $this->getHeaders())){
		//if(mail($this->getToEmail(), $this->getSubject(), $message)){
		    echo 'Your mail has been sent successfully.';
		} else{
		    $cErr->Error('Unable to send email.');
		}
	}
    function makeHeaders(){
        $string = "MIME-Version: 1.0\r\n";
        $string .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $string .= "From: \"{$this->getFromName()}\" <{$this->getFromAddress()}>\r\n";
        $string .= "Reply-To: \"{$this->getFromName()}\" <{$this->getFromAddress()}>\r\n";
        $string .= "X-Mailer: " . phpversion();
        return $string;
    }
    function makeFormattedSubject(){
        global $site_settings;
        return "[{$site_settings->getKey('SITE_SHORT_TITLE')}] {$this->getSubject()}";
    }
    function makeFormattedMessage(){
        //CT uses a template to make it look nice
        global $p;
        $string = file_get_contents(TEMPLATES_PATH . '/mail_admin.html', TRUE);
        $string = $p->ReplacePlaceholders($string);
        $string = $p->ReplaceVarInString($string, '$message', $this->getMessage());
        return $string;
    }


    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param mixed $from_name
     *
     * @return self
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->from_address;
    }

    /**
     * @param mixed $from_address
     *
     * @return self
     */
    public function setFromAddress($from_address)
    {
        $this->from_address = $from_address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhpVersion()
    {
        return $this->php_version;
    }

    /**
     * @param mixed $php_version
     *
     * @return self
     */
    public function setPhpVersion($php_version)
    {
        $this->php_version = $php_version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->to_name;
    }

    /**
     * @param mixed $to_name
     *
     * @return self
     */
    public function setToName($to_name)
    {
        $this->to_name = $to_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToAddress()
    {
        return $this->to_address;
    }

    /**
     * @param mixed $to_address
     *
     * @return self
     */
    public function setToAddress($to_address)
    {
        $this->to_address = $to_address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     *
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormattedSubject()
    {
        return $this->formatted_subject;
    }

    /**
     * @param mixed $formatted_subject
     *
     * @return self
     */
    public function setFormattedSubject($formatted_subject)
    {
        $this->formatted_subject = $formatted_subject;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormattedMessage()
    {
        return $this->formatted_message;
    }

    /**
     * @param mixed $formatted_message
     *
     * @return self
     */
    public function setFormattedMessage($formatted_message)
    {
        $this->formatted_message = $formatted_message;

        return $this;
    }
}

?>