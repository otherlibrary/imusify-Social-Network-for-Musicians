		<div class="login-content">
			<div class="alert alert-danger" style="display:none;"></div>
			<div class="alert alert-success" style="display:none;"></div>
			<form method="post"  role="form" id="form_rp">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="rst_password" id="rst_password" placeholder="Password" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="cm_rst_password" id="cm_rst_password" placeholder="Confirm Password" autocomplete="off" />
						<input type="hidden"  name="fp_code" value="<?= $fp_code ?>"/>
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						Reset Your Password
						<i class="entypo-login"></i>
					</button>
				</div>
				
			</form>
		</div>
		