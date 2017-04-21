<ol class="breadcrumb bc-3">
						<li>
				<a href="index.html"><i class="entypo-home"></i>Home</a>
			</li>
					
				<li class="active">
			
							<strong><?php print $action ?> Genre</strong>
					</li>
					</ol>
			
<h2><?php print $action ?> Genre</h2>
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
				<?php $attributes = array('class' => 'form-horizontal form-groups-bordered','name'=>'form_genre' ,'id' => 'form_genre'); ?>
				<?php echo form_open(base_url().'admin/genre/'.strtolower($action).'/'.$id,$attributes); ?>
					<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
					<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>
					
					
					
				
					<div class="form-group">
						<label for="genre" class="col-sm-3 control-label">Genre</label>
						
						<div class="col-sm-5">
							<input type="text" name="genre" id="genre" class="form-control validate[required,maxSize[20]] text-input"  placeholder="Enter genre" value="<?php print $genre; ?>">
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
					<label for="is_default" class="col-sm-3 control-label">Genre Type</label>
						<div class="col-sm-5">
							<div class="radio">
								<label>
									<input type="radio" name="type" id="optionsRadios3" value="p" <?php if($type=='p') { ?> checked="checked" <?php } ?>>Primary
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" id="optionsRadios4" value="s" <?php if($type=='s') { ?> checked="checked" <?php } ?>>Secondary
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




