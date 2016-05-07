<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">
		<strong>Detail</strong>
	</li>
</ol>
<h2>Membership Detail</h2>
<br />
<?php if (function_exists('validation_errors') AND validation_errors() != ''){?>
<div class="alert alert-danger"><?php echo  validation_errors(); ?></div>
<?php 
}
?>
<div class="row">
	<div class="col-md-12">
		
		<div class="alert alert-success" style="display:none;">$this->session->flashdata('item');</div>
		
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
				<div class="form-horizontal form-groups-bordered">

					<div class="form-group">
						<label for="genre" class="col-sm-3 control-label">Title</label>
						<div class="col-sm-9">
							<?php print $plan_details["name"]; ?>						
						</div>
					</div>

					<div class="form-group">
						<label for="genre" class="col-sm-3 control-label">Description</label>
						<div class="col-sm-9">
							<?php print $plan_details["description"]; ?>						
						</div>
					</div>

					<div class="form-group">
						<label for="status" class="col-sm-3 control-label">Amount</label>
						<div class="col-sm-5">
							<?php print $plan_details["amount"]; ?>
						</div>
					</div>

					<?php foreach ($plan_details["plan_details"] as $key => $value) {
						?>
						<div class="form-group">
							<label for="status" class="col-sm-3 control-label"><?php echo $value["text"];?></label>
							<div class="col-sm-5">
								<?php if($value["value"] == 'y') 
										echo 'Yes';
									else
										echo 'No';	
								?>
							</div>
						</div>	
						<?php
					} ?>
				</div>
			</div>		
		</div>	
	</div>
</div>




