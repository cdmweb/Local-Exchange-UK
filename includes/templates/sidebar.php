<!--START include sidebar -->
	<div class="sidebar">
		<ul class="menu">
			<li><a href="{{SERVER_PATH_URL}}/index.php">Home</a></li>
			<li><a href="{{SERVER_PATH_URL}}/pages.php?page_id=7">Information</a></li>
			<li><a href="{{SERVER_PATH_URL}}/pages.php?page_id=84">News &amp; events</a></li>
			<li><a href="{{SERVER_PATH_URL}}/listings.php?type=O&timeframe=14">Offered</a></li>
			<li><a href="{{SERVER_PATH_URL}}/listings.php?type=W&timeframe=14">Wanted</a></li>
			<li><a href="{{SERVER_PATH_URL}}/member_directory.php">Member directory</a></li>
			<li><a href="{{SERVER_PATH_URL}}/contact.php">Contact us</a></li>
		</ul>
		<br />
		<ul class="menu">
			{{admin_menu_item}}
			<li><a href="{{SERVER_PATH_URL}}/member_dashboard.php">My dashboard</a></li>
			<!-- <li><a href="{{SERVER_PATH_URL}}/trade_history.php">My trades</a></li> -->
			<li><a href="{{SERVER_PATH_URL}}/{{login_toggle_link}}">{{login_toggle_text}}</a></li>
		</ul>
	</div>
<!--END include sidebar -->