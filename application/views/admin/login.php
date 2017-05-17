		<div class="login-content">
			<div class="alert alert-danger" style="display:none;"></div>
			<div class="alert alert-success" style="display:none;"></div>
			<form method="post"  role="form" id="form_login">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						Login In
						<i class="entypo-login"></i>
					</button>
				</div>
				
			</form>
			
			<form method="post"  role="form" id="form_fp" style="display:none;">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="email" id="email" placeholder="Email Address" autocomplete="off" />
					</div>
					
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						Submit
						<i class="entypo-login"></i>
					</button>
				</div>
				
			</form>	
			
			
			<div class="login-bottom-links">
				<a href="javascript:void(0);" id="fp_link" class="link">Forgot your password?</a>
				
				<br />
				
				<a href="#">Terms of Services</a>  - <a href="#">Privacy Policy</a>
				</div>
		</div>
		