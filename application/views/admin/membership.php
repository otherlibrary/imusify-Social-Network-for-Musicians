<h2>Manage Membership Plan</h2>
<br>
<?php
if($this->session->flashdata('msg') != '')
{
	?>
	<div class="alert alert-success"><?php print $this->session->flashdata('msg'); ?></div>
	<?php
}
?>
<br>
<div class="table-toolbar">
	<div class="row">
		<div class="col-md-6">

		</div>
		<div class="col-md-6">
			<div class="btn-group pull-right">
				<div class="btn-group">
					<a href="<?php print base_url(); ?>admin/membership/membership_update_plans" class="btn btn-primary">
						<i class="entypo-plus"></i>
						Import Braintree Plans
					</a>					
				</div>				
			</div>
		</div>
	</div>
</div>
<div id="table-3_wrapper" class="dataTables_wrapper form-inline" role="grid">
	<table class="table table-bordered datatable dataTable" id="table-membership" aria-describedby="table-3_info">
		<thead>
			<tr role="row">
				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 150px;">Plan Id</th>

				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 150px;">Plan Name</th>

				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 220px;">Description</th>

				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 220px;">Amount</th>

				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 101px;">Status</th>

				<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 400px;">Actions</th>					
			</tr>
		</thead>
	</table>
	