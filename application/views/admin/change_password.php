<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">

		<strong>Change Password</strong>
	</li>
</ol>

<h2>Change Password</h2>
<br />




<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger" style="display:none;"></div>
		<div class="alert alert-success" style="display:none;"></div>
		
		<div class="panel panel-primary" data-collapsed="0">

			
			
			<div class="panel-body">
				
				<form role="form" id="form_cp" name="form_cp" class="form-horizontal form-groups-bordered">

					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Password</label>
						
						<div class="col-sm-5">
							<input type="password" name="password" id="password" class="form-control validate[required,maxSize[20]] text-input"  placeholder="Password">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-3"  class="col-sm-3 control-label">New Password</label>
						
						<div class="col-sm-5">
							<input type="password" name="nw_password" id="nw_password" class="form-control validate[required,minSize[6],maxSize[20]] text-input" id="field-3" placeholder="New Password">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Confirm Password</label>
						
						<div class="col-sm-5">
							<input type="password" name="cm_password" id="cm_password" class="form-control validate[required,equals[nw_password]] text-input" id="field-3" placeholder="Confirm Password">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-default" id="change_password">Change</button>
						</div>
					</div>
				</form>
				
			</div>

		</div>

	</div>
</div>




