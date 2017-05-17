<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">

		<strong><?php print $action ?> Invitation</strong>
	</li>
</ol>

<h2><?php print $action ?> Invitation Code</h2>
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
				<?php echo form_open(base_url().'admin/invitation/'.strtolower($action).'/'.$id,$attributes); ?>
				<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
				<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Code</label>

					<div class="col-sm-9">
						<input type="text" name="code" id="code" class="form-control validate[required,maxSize[255]] text-input" placeholder="Enter Code" value="<?php print $code; ?>">
					</div>
				</div>

				<!-- <div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Created Date</label>
					<div class="col-sm-9">
						<input type="text" name="url" id="url" class="form-control text-input datepicker"  placeholder="Enter select date" value="<?php print $url; ?>">
					</div>
				</div> -->

				<!-- <div class="form-group">
					<label for="genre" class="col-sm-3 control-label">End Date</label>
					<div class="col-sm-9">
						<input type="text" name="url" id="url" class="form-control text-input"  placeholder="Enter select date" value="<?php //print $url; ?>">
					</div>
				</div> -->


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




