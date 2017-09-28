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
		
		if ($cUser->member_role > 0)
			$this->AddSidebarButton("Administration", "admin_menu.php");	
	}		
									
	function AddSidebarButton ($button_text, $url) {
		$this->sidebar_buttons[] = new cMenuItem($button_text, $url);
	}
	
	function AddTopButton ($button_text, $url) { // Top buttons aren't integrated into header yet...
		$this->top_buttons[] = new cMenuItem($button_text, $url);
	}

	function MakePageHeader() {
		global $cUser;
		
		if(isset($this->page_title)) 
			$title = $this->page_title . ": ";
		else
			$title = "";
		
		$output = "<!DOCTYPE HTML>
		<html>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
		<meta name='viewport' content='user-scalable=yes'/>
		<title>". $title . PAGE_TITLE_HEADER ."</title>
		<link rel='stylesheet' href='http://". HTTP_BASE ."/". SITE_STYLESHEET ."'' type='text/css'></link>";
		$output .= "<body><div class=\"page\">";
		$output .= $this->page_header ;

	
		return $output;
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
		global $cUser;
		$output .= "<div class=\"footer\">";
		$output .= $this->page_footer ."</div></div>";
		$output .= "</body></html>";
		return $output;
	}	
	function MakePageContent() {
		//global $cUser;
		if(strlen($this->page_content)<1) { 
			// set error message - something is wrong!
			$this->AddError('No page content set');
			$this->page_content = "Nothing to show.";
		}
		$output .= "<div class=\"content\">";
		$output .= $this->MakePageTitle();
		//$output .= $cError;
		$output .= $this->MakeErrorContent();
		//$output .= $content;
		$output .= $this->page_content ."</div>";
		return $output;
	}
	function MakeErrorContent() {
		//global $cUser;
		global $cErr;
		//CT: fix this. append errors sent by old system to show both
		//$this->errors=array_merge($this->errors,$cErr->arrErrors);
		//$this->errors=array_merge($this->errors,$cErr->arrErrors);
		//$this->AddError("test");
		if(count($cErr->arrErrors)<1) return "";
		$output = "<div class=\"errors\"><p>Messages:</p><ul>";
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
		$output = $this->MakePageHeader();
		$output .= "<div class=\"main\">";
		$output .= $this->MakePageMenu();
		$output .= $this->MakePageContent();
		$output .= "</div>";
		$output .= $this->MakePageFooter();
		print $output;
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
