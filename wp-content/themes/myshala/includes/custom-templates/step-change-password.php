<div class="step" id="step-<?php echo $agc_login_step;?>">
	<form class="form-horizontal" action="" method="post">
		<fieldset>
			<div id="legend" class="">
				<legend class="">Password Change</legend>
			</div>
			<p>Almost there! Change your password to keep your account secure.</p>
			
			<div class="agcPasswordError"></div>
			
			<!-- OLD PASSWORD -->
			<div class="control-group">
						
				<!-- Text input-->
				<label class="control-label" for="agc_old_password">Old Password</label>
				<div class="controls">
					<input name="agc_old_password" id="agc_old_password" type="password" placeholder="******" class="input-xlarge">
					<p class="help-block meta">Enter the old password</p>
				</div>
			</div>
			
			<!-- NEW PASSWORD -->
			<div class="control-group">
						
				<!-- Text input-->
				<label class="control-label" for="agc_new_password">New Password</label>
				<div class="controls">
					<input name="agc_new_password" id="agc_new_password" type="password" placeholder="******" class="input-xlarge">
					<p class="help-block meta">Enter a new password</p>
				</div>
			</div>
			
			<!-- RE ENTER PASSWORD -->
			<div class="control-group">
				<!-- Text input-->
				<label class="control-label" for="agc_re_new_password">Re-enter Password</label>
				<div class="controls">
					<input type="password" id="agc_re_new_password" name="agc_re_new_password" placeholder="******" class="input-xlarge">
					<p class="help-block meta">Re-enter your new password</p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"></label>
					<!-- Button -->
					<div class="controls">
						<div class="next-step button size-small" id="agcChangePassword">Done!</div>
						<span class="loading-16" id="loading-acgChangePassword" style="display:none;vertical-align:middle;margin-left:10px"></span>
					</div>
				</div>

		</fieldset>
	</form>
</div><!-- /#Final Step -->