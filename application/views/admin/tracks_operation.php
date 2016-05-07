<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">
		<strong><?php print $action ?> Tracks</strong>
	</li>
</ol>
<h2><?php print $action ?> Tracks</h2>
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
				<?php echo form_open(base_url().'admin/article/'.strtolower($action).'/'.$id,$attributes); ?>
				<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
				<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Title</label>
					<div class="col-sm-9">
						<input type="text" name="title" id="title" class="form-control validate[required,maxSize[255]] text-input" placeholder="Enter Title" value="<?php print $title; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="genre" class="col-sm-3 control-label">Description</label>
					<div class="col-sm-9">
						<textarea id="editor1" name="description">
							<?php print $description;?>
						</textarea>
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
					<label for="status" class="col-sm-3 control-label">Display on headlines</label>
					<div class="col-sm-5">
						<div class="radio">
							<label>
								<input type="radio" name="headlineDisp" id="optionsRadios1" value="y" <?php if($headlineDisp=='y') { ?> checked="checked" <?php } ?>>Active
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="headlineDisp" id="optionsRadios2" value="n" <?php if($headlineDisp=='n') { ?> checked="checked" <?php } ?>>Inactive
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




