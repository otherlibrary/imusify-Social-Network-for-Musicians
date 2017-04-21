<ol class="breadcrumb bc-3">
	<li>
		<a href="index.html"><i class="entypo-home"></i>Home</a>
	</li>

	<li class="active">

		<strong><?php print $action ?> Sub Admin</strong>
	</li>
</ol>

<h2><?php print $action ?> Sub Admin</h2>
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
				
				<?php $attributes = array('class' => 'form-horizontal form-groups-bordered','name'=>'form_user_role' ,'id' => 'form_user_role'); ?>
				<?php echo form_open(base_url().'admin/sub_admin/'.strtolower($action).'/'.$id,$attributes); ?>

				<?php 
				if($action == "Add")
				{
					?>

					<div class="form-group">
						<label for="user_role" class="col-sm-3 control-label">Firstname</label>
						<div class="col-sm-5">
							<input type="text" name="firstname" id="firstname" class="form-control validate[required] text-input" placeholder="Firstname" value="<?php print $firstname; ?>">
						</div>
					</div>	

					<div class="form-group">
						<label for="user_role" class="col-sm-3 control-label">Lastname</label>
						<div class="col-sm-5">
							<input type="text" name="lastname" id="lastname" class="form-control text-input" placeholder="Lastname" value="<?php print $lastname; ?>">
						</div>
					</div>


					<div class="form-group">
						<label for="user_role" class="col-sm-3 control-label">Username</label>
						<div class="col-sm-5">
							<input type="text" name="username" id="username" class="form-control validate[required] text-input"  placeholder="Username" value="<?php print $username; ?>">
						</div>
					</div>	

					<div class="form-group">
						<label for="user_role" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-5">
							<input type="text" name="email" id="email" class="form-control validate[required text-input"  placeholder="Email" value="<?php print $email; ?>">
						</div>
					</div>
					<?php 		
				}
				?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Roles:</label>
					<div class="col-sm-7">
						<select multiple="multiple" name="my-select[]" class="form-control multi-select">
							<?php foreach($result as $row){?> 
							<option value="<?php print $row['id']; ?>" <?php if($row['checked']) { ?> selected <?php } ?>><?php  print $row['title'];  ?></option>
							<?php }  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>




