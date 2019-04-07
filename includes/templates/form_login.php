<!--START include form_login -->
<p>You must be logged in to access this section of the site. </p>
<div class="form">
	<form method="post" action="{{HTTP_BASE}}/login.php" class="layout1"> 
		<input type="hidden" name="action" value="login" />
		<p class="l_text">
			<label>
				<span>Member ID:</span>
				<input type="text" name="user" id="user" value="" />
			</label>
		</p>
		<p class="l_password">
			<label>
				<span>Password:</span>
				<input type="password" name="pass" id="pass" value="" />
			</label>
		</p>
		<p class="l_submit"><input type="submit" value="Log in" name="submit" id="submit" /></p>
	</form>
<p><a href="{{HTTP_BASE}}/password_reset.php">Forgot your password?</a> | <a href="{{HTTP_BASE}}/pages.php?id={{PAGE_ID_JOINING}}">Join us</a></p>
</div>
<!--END include form_login -->