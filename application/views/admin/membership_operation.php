<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">
		<strong><?php print $action ?> Membership</strong>
	</li>
</ol>
<h2><?php print $action ?> Membership</h2>
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
				<?php $attributes = array('class' => 'form-horizontal form-groups-bordered','name'=>'form_invitation' ,'id' => 'form_invitation'); ?>
				<?php echo form_open(base_url().'admin/membership/'.strtolower($action).'/'.$plan_details["id"],$attributes); ?>
				<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
				<input type="hidden" name="id" id="id" value="<?php print $plan_details["id"];?>"/>
				<?php foreach ($plan_details["plan_details"] as $key => $value) {
					?>

					<?php if($value["value"] == "y" || $value["value"] == "n"){?>	

					<div class="form-group">
					<label for="status" class="col-sm-3 control-label"><?php echo $value["text"]; ?></label>
						<div class="col-sm-5">
							<div class="radio">
								<label>
									<input type="radio" name="<?php echo $value["name"]; ?>" id="optionsRadios1" value="y" <?php if($value["value"]=='y') { ?> checked="checked" <?php } ?>>Yes
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="<?php echo $value["name"]; ?>" id="optionsRadios2" value="n" <?php if($value["value"]=='n') { ?> checked="checked" <?php } ?>>No
								</label>
							</div>
						</div>
					</div>


					<?php }else{ ?>
					<div class="form-group">

						<?php if($value["name"] == "space") 
								$temp = $value["text"]." in KB ";
							  else
								$temp = $value["text"];		
						?>
						<label for="genre" class="col-sm-3 control-label"><?php echo $temp; ?></label>

						<div class="col-sm-9">
							<input type="text" name="<?php echo $value["name"]; ?>" id="title" class="form-control validate[required text-input" placeholder="Enter <?php echo $value["name"]; ?>" value="<?php print $value["value"]; ?>">
						</div>
					</div>
					<?php } ?>
					<?php
					} ?>

				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-default"><?php print $action; ?></button>
					</div>
				</div>
			</form>

		</div>
		
	</div>
	
</div>
</div>




