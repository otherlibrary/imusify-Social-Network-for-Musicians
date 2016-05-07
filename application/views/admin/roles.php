<h2>Manage Roles</h2>
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
											<a href="<?php print base_url(); ?>admin/roles/add" class="btn btn-primary">
	<i class="entypo-plus"></i>
	Add Row
</a>
										</div></div>
									</div>
								</div>
</div>
<div id="table-3_wrapper" class="dataTables_wrapper form-inline" role="grid">
<table class="table table-bordered datatable dataTable" id="table-3" aria-describedby="table-3_info">
	<thead>
		<tr role="row">
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 220px;">Role</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 117px;">Created Date</th>

		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 101px;">Status</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 101px;">Default</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 301px;">Actions</th>
		
		
		</tr>
	</thead>
	
	

		
		</table>
		<!--
		<div class="row"><div class="col-xs-6 col-left"><div class="dataTables_info" id="table-2_info">Showing 1 to 8 of 12 entries</div></div><div class="col-xs-6 col-right"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination pagination-sm"><li class="prev disabled"><a href="#"><i class="entypo-left-open"></i></a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li class="next"><a href="#"><i class="entypo-right-open"></i></a></li></ul></div></div></div></div>-->