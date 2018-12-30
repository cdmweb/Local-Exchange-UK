<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

class cPage {
	var $page_title;
	var $page_title_image; // Filename, no path
	var $page_content; // Filename, no path
	var $page_header;	// HTML
	var $page_footer;	// HTML
	var $keywords;		
	var $site_section;
	var $sidebar_buttons; 	// An array of cMenuItem objects
	var $top_buttons;		// An array of cMenuItem objects    TODO: Implement top buttons...
	var $errors;			// array. CT: added for debugging. todo - show for admin only? array.
	var $page_msg;			// CT: todo - show actions completed and other messages?

	function cPage() {
		global $cUser, $SIDEBAR;
		
		$this->keywords = SITE_KEYWORDS;
		$this->page_header = PAGE_HEADER_CONTENT;
		$this->page_footer = PAGE_FOOTER_CONTENT;
		
		foreach ($SIDEBAR as $button) {
			$this->AddSidebarButton($button[0], $button[1]);
		}
		
		if ($cUser->getMemberRole() > 0)
			$this->AddSidebarButton("Administration", "admin_menu.php");	
	}		
									
	function AddSidebarButton ($button_text, $url) {
		$this->sidebar_buttons[] = new cMenuItem($button_text, $url);
	}
	
	function AddTopButton ($button_text, $url) { // Top buttons aren't integrated into header yet...
		$this->top_buttons[] = new cMenuItem($button_text, $url);
	}

	function MakeDocHeader() {
		global $cUser;
		
		if(isset($this->page_title)) 
			$title = $this->page_title . ": ";
		else
			$title = "";
		
		return "<!DOCTYPE HTML>
		<html>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
		<meta name='viewport' content='user-scalable=yes'/>
		<title>". $title . PAGE_TITLE_HEADER ."</title>
		<link rel='stylesheet' href='http://". HTTP_BASE ."/". SITE_STYLESHEET ."'' type='text/css'></link></head><body>";
	}
	function MakePageHeader() {
		return $this->page_header ;
	}
	//CT: simple adding of errors
	function AddError($error){
		$this->errors[] = $error;
	}
	function MakePageMenu() {
		global $cUser, $cSite, $cErr;
	
		$output = "<div class=\"sidebar\"><ul class=\"menu\">";
	
		foreach ($this->sidebar_buttons as $menu_item) {
			$output .= $menu_item->DisplayButton();
		}
	
        $output .= "<li>" . $cUser->UserLoginLogout() . "</li>";
		$output .= "</ul></div>";
		return $output;
	}

	function MakePageTitle() {
		global $SECTIONS;
		
		if (!isset($this->page_title) or !isset($this->site_section)) {
			return "";
		} else {
			if (!isset($this->page_title_image))
				$this->page_title_image = $SECTIONS[$this->site_section][2];
			return "<h2>$this->page_title</h2>";
			//CT: style choice - removing the image mucks with alignment of text/titles	
			//return '<H2><IMG SRC="http://'. IMAGES_PATH . $this->page_title_image .'" align=middle>'. $this->page_title .'</H2><P>';
		}		
	}
									
	function MakePageFooter() {
		return $this->Wrap($this->page_footer, "div", "footer");
	}	
	function MakeDocFooter() {
		return "</body></html>";
	}	

	function MakePageContent() {
		//global $cUser;
		if(strlen($this->page_content)<1) { 
			// set error message - something is wrong!
			$this->AddError('No page content set');
			$this->page_content = "Nothing to show.";
		}

		$content = $this->MakePageTitle() . $this->MakeErrorContent() . $this->page_content;
		return $this->Wrap($content, "div", "content");
	}
	function MakeErrorContent() {
		//global $cUser;
		global $cErr;
		//CT: fix this. append errors sent by old system to show both
		//$this->errors=array_merge($this->errors,$cErr->arrErrors);
		//$this->errors=array_merge($this->errors,$cErr->arrErrors);
		//$this->AddError("test");
		if(count($cErr->arrErrors)<1) return "";
		//$output = "<div class=\"errors\"><p>Messages:</p><ul>";
		$output = "<div class=\"errors\"><ul>";
		//var_dump
		foreach ($cErr->arrErrors as $error) {
			$output .= '<li>'. $error[1] . "</li>";
		}
		$output .= "</ul></div>";
		return $output;
	}	
	//CT: transitional functin - should be backwards compativle.		
	function DisplayPage($content="") {
		global $cErr, $cUser;

		if(strlen($content)>0) $this->page_content = $content;
		//print "cnte" . $p->page_content;
		$header = $this->MakePageHeader();
		//$output .= "<div class=\"main\">";
		$main = $this->MakePageMenu() . $this->MakePageContent();
		$main = $this->Wrap($main, "div", "main");
		$footer = $this->MakePageFooter();
		$page = $this->Wrap($header . $main . $footer, "div", "page");
		//CT - wrap in div for control
		$output = $this->MakeDocHeader() . $page . $this->MakeDocFooter();
		print $output;
	}
	function MenuItemArray($string, $link){
		$arr = array(
			"string" => $string, 
			"link" => $link
		);
		return $arr;
	}	
	function Menu($array){
		//$array is text, link;
		$menu = "";
		foreach ($array as $key => $value){
			$menu .= $this->Wrap($value['string'], "li", null, $value['link']);
			//$menu .= $p->Wrap($value['string'], "li", null, $value['link']);
		}
		return $this->Wrap($menu, "ul");
	}
	function Wrap($string, $elementName, $cssClass=null, $link=null){
		if(!empty($link)){
			$string = $this->Link($string, $link);
		}
		if(!empty($cssClass)){
			$cText=" class='{$cssClass}'";
		}
		return "<{$elementName} {$cText}>{$string}</{$elementName}>";
	}
	function Link($string, $link){
		return "<a href='{$link}'>{$string}</a>";
	}
	function WrapForm($string, $action, $method="get", $cssClass=""){
		//default method as get, and cssclass
		return "<form action='{$action}' method='{$method}' class='{$cssClass}'>{$string}</form>";
	}
	function WrapFormElement($type, $name, $label='', $value='', $cssClass=''){
		switch ($type){
			case 'text':
				$output = "<label class='text'><span>{$label}:</span><input type='text' value='{$value}' name='{$name}'  /></label>";
			break;
			case 'password':
				$output = "<label class='password'><span>{$label}:</span><input type='password' name='{$name}' /></label>";
			break;
			case 'hidden':
				$output = "<input type='hidden' value='{$value}' name='{$name}' /></label>";
			break;
			case 'textarea': 
				$output = "<label class='textarea'><span>{$label}:</span><texarea value='{$value}' name='{$name}' /></label>";
			break;
			case 'checkbox':
				$output = "<label class='checkbox'><input type='text' value='{$value}' name='{$name}' /><span>{$label}</span></label>";
			break;
			case 'submit':
				$output = "<input type='submit' value='{$value}' name='{$name}' />";
			break;
			default:
				$output= 'nothing';
				//
		}
		if($type !="hidden") $output=$this->Wrap($output, 'div', "l_".$type);
		return $output;
	}
	
	
}

class cMenuItem {
	var $button_text;
	var $url;
	
	function cMenuItem ($button_text, $url) {
		$this->button_text = $button_text;
		$this->url = $url;
	}
	
	function DisplayButton() {
		return "<li><a href=\"http://". HTTP_BASE ."/". $this->url ."\">". $this->button_text ."</a></li>";

        // The following is for url-based sessions.
//		return "<li><div align=left><a href=\"" . $this->url ."\">". $this->button_text ."</a></div></li>";
	}
}

$p = new cPage;

?>
