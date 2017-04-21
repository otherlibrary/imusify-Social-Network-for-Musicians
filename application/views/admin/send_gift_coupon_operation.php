<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>
	<li class="active">
		<strong><?php print $action ?> Gift Coupon send</strong>
	</li>
</ol>

<h2><?php print $action ?> Gift Coupon send</h2>
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
				<?php echo form_open(base_url().'admin/send_gift_coupon/'.strtolower($action).'/'.$id,$attributes); ?>
				<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
				<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-5">
						<input type="text" name="email" id="email" class="form-control text-input invite_email_tagmanage" placeholder="Enter Email (mulitple comma seperated)" value="">
					</div>
					<label for="genre" style="display:none;" class="control-label redlabel" id="invalid_email_label">Invalid Email</label>
				</div>

				<div class="form-group" style="display:none;" id="display_invited_cont">
					<label for="genre" class="col-sm-3 control-label">Invited Email</label>
					<div class="col-sm-5">						
						<div id="testcontainer"></div>
					</div>
				</div>
			
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Coupon</label>
					<div class="col-sm-5">
						<select name="code" id="code" class="form-control">
							<option value="">Please select invitation code</option>
							<?php foreach ($codelist as $key => $value) {
							?>
							<option value="<?php echo $value["id"]; ?>" <?php print ($code==$value["id"])?"selected":"";?>><?php echo $value["code"]; ?></option>
							<?php
							} ?>
						</select>
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




