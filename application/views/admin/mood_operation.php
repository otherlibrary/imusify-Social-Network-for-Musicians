<ol class="breadcrumb bc-3">
						<li>
				<a href="index.html"><i class="entypo-home"></i>Home</a>
			</li>
					
				<li class="active">
			
							<strong><?php print $action ?> Mood</strong>
					</li>
					</ol>
			
<h2><?php print $action ?> Mood</h2>
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
				<?php $attributes = array('class' => 'form-horizontal form-groups-bordered','name'=>'form_mood' ,'id' => 'form_mood'); ?>
				<?php echo form_open(base_url().'admin/mood/'.strtolower($action).'/'.$id,$attributes); ?>
					<input type="hidden" name="action" id="action" value="<?php print $action;?>"/>
					<input type="hidden" name="id" id="id" value="<?php print $id;?>"/>
							
					<div class="form-group">
						<label for="mood" class="col-sm-3 control-label">Mood</label>
						
						<div class="col-sm-5">
							<input type="text" name="mood" id="mood" class="form-control validate[required,maxSize[20]] text-input"  placeholder="Enter mood" value="<?php print $mood; ?>">
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




