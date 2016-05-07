<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>
	<li class="active">
		<strong><?php print $action ?> Membership Coupons</strong>
	</li>
</ol>

<h2><?php print $action ?> Membership Coupons</h2>
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
				<?php $attributes = array('class' => 'form-horizontal form-groups-bordered','name'=>'form_invite' ,'id' => 'form_invite'); ?>
				<?php echo form_open(base_url().'admin/membership_coupons/'.strtolower($action).'/'.$id,$attributes); ?>
				<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
				<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>				
				
				<?php if($id > 0){ ?>
				<div class="form-group">
					<label for="status" class="col-sm-3 control-label">Type</label>
					<div class="col-sm-5">
						<?php echo ($type == 's') ? "Space" : ($type == 'p') ? "Premium" : ""; ?>		
					</div>
				</div>
				<?php }else{ ?>
				<div class="form-group">
					<label for="status" class="col-sm-3 control-label">Type</label>
					<div class="col-sm-5">
						<div class="radio">
							<label>
								<input type="radio" class="type_radio" name="type" id="optionsRadios1" value="t" <?php if($type=='t') { ?> checked="checked" <?php } ?>>Time based premium code
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" class="type_radio" name="type" id="optionsRadios2" value="s" <?php if($type=='s') { ?> checked="checked" <?php } ?>>Space
							</label>
						</div>
					</div>
				</div>
				<?php } ?>	
				<?php if($id > 0){ ?>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Code</label>
					<label><?php echo $code; ?></label>
				</div>					
				
				<?php }else{ ?>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Code</label>
					<div class="col-sm-5">
						<input type="text" class="text-input form-control" name="code" value="<?php echo ($code != "") ? $code : ""; ?>">
					</div>					
				</div>
				<?php } ?>		
				
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Month Limit</label>
					<div class="col-sm-5">
						<!-- <input type="number" min="1" max="12" class="text-input form-control" name="month_limit" value="<?php echo ($month_limit != "") ? $month_limit : ""; ?>"> -->
						<select class="text-input form-control" name="month_limit">
							<?php for ($i=1; $i<=12;$i++) { ?>
							<option <?php if($i == $month_limit) { ?> selected <?php } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
							<option value="l">Lifetime</option>	
						</select>
					</div>					
				</div>

				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Plan Name</label>
					<div class="col-sm-5">
						Premium
					</div>					
				</div>
				
				<div class="form-group displaynone hiddencontent">
					<label for="genre" class="col-sm-3 control-label">Space Allowed</label>
					<div class="col-sm-5">
						<input type="text" class="text-input form-control" name="space_allowed" value="<?php echo ($space_allowed != "") ? $space_allowed : ""; ?>">
					</div>					
				</div>

				<div class="form-group">
					<label for="status" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-5">
						<div class="radio">
							<label>
								<input type="radio" name="status" id="optionsRadios1" value="y" <?php if($status=='y') { ?> checked="checked" <?php } ?>>Active
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="status" id="optionsRadios2" value="n" <?php if($status=='n') { ?> checked="checked" <?php } ?>>Inactive
							</label>
						</div>
					</div>
				</div>

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




