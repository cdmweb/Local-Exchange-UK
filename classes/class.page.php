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
		global $cUser, $SIDEBAR, $site_settings;
		
		$this->keywords = SITE_KEYWORDS;
		//print_r('page');
		//print_r($site_settings);
		//print_r($site_settings->getKey('SITE_SHORT_TITLE'));
		$string = file_get_contents(TEMPLATES_PATH . '/header.php', TRUE);
		$this->page_header = $this->ReplacePlaceholders($string);

		$string = file_get_contents(TEMPLATES_PATH . '/footer.php', TRUE);
		$this->page_footer = $this->ReplacePlaceholders($string);
		
		if ($cUser->getMemberRole() > 0)
			$this->AddSidebarButton("Administration", "admin_menu.php");

		foreach ($SIDEBAR as $button) {
			$this->AddSidebarButton($button[0], $button[1]);
		}

	}		
	//CT replaces strings {LIKE-THIS} with either settings file strings
	// this is a temporary mesasure til we get moustache or a proper template engine
	function ReplacePlaceholders($string){
		global $site_settings;
		//CT first replace the bits that are from constants
		$string = str_replace("{HTTP_BASE}",HTTP_BASE,$string);
		$string = str_replace("{IMAGES_PATH}",IMAGES_PATH,$string);
		$string = str_replace("{STYLES_PATH}",STYLES_PATH,$string);
		$string = str_replace("{LOCALX_VERSION}",LOCALX_VERSION,$string);
		$string = $this->ReplacePropertiesInString($string);
		// CT then do properly
		return $string;
	}			
	function ReplaceVarInString($string, $varname, $value){
		return str_replace("{" . $varname . "}", $value, $string);
	}

	function ReplacePropertiesInString($string){
		global $site_settings;
		$variables = $site_settings->getStrings();
		foreach($variables as $key => $value){
		    $string = str_replace("{" . $key . "}", $value, $string);
		}
		return $string;
	}		
	function AddSidebarButton ($button_text, $url) {
		$this->sidebar_buttons[] = new cMenuItem($button_text, $url);
	}
	
	function AddTopButton ($button_text, $url) { // Top buttons aren't integrated into header yet...
		$this->top_buttons[] = new cMenuItem($button_text, $url);
	}

	function MakeDocHeader() {
		global $cUser, $site_settings;
		
		$title = (isset($this->page_title)) ? $this->page_title : "";
		$string = file_get_contents(TEMPLATES_PATH . '/doc_header.php', TRUE);
		$string = $this->ReplacePlaceholders($string);
		$string = $this->ReplaceVarInString($string, '$title', $title);
		return $string;
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
			//return '<H2><IMG SRC=" . IMAGES_PATH . $this->page_title_image .'" align=middle>'. $this->page_title .'</H2><P>';
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
	//CT: new funtion for layout of text

	function Wrap($string, $elementName, $cssClass=null, $link=null){
		if(!empty($link)){
			$string = $this->Link($string, $link);
		}
		if(!empty($cssClass)){
			$cText=" class='{$cssClass}'";
		}
		return "<{$elementName} {$cText}>{$string}</{$elementName}>";
	}
	public function WrapLabelValue($label, $value){
		$separator=":";
		$label=$this->Wrap($label . $separator . " ", "span", "label");
		$value=$this->Wrap($value, "span", "value");
		return $this->Wrap($label.$value, "p", "line");
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
	function FormatShortDate($d){
		global $site_settings;
		//localise to country
		return date_format(date_create($d), $site_settings->getKey('SHORT_DATE'));
	}
	function FormatLongDate($d){
		//localise to country
		return date_format(date_create($d), $site_settings->getKey('LONG_DATE'));

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
		if ($this->url=="" || $this->button_text ==""){
			$button = "<li><br /></li>";
		} else{
			$button = "<li><a href=\"". HTTP_BASE . "/" . $this->url ."\">". $this->button_text ."</a></li>";

		}
		return $button;

        // The following is for url-based sessions.
//		return "<li><div align=left><a href=\"" . $this->url ."\">". $this->button_text ."</a></div></li>";
	}
}

$p = new cPage;

?>
