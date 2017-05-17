<h2>Manage Users</h2>
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

<div id="table-2_wrapper" class="dataTables_wrapper form-inline" role="grid">
<table class="table table-bordered datatable dataTable" id="table-2" aria-describedby="table-2_info">
	<thead>
		<tr role="row">
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending" style="width: 220px;">Username</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 117px;">Email</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Email / Occupation: activate to sort column ascending" style="width: 276px;">Description</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Email / Occupation: activate to sort column ascending" style="width: 276px;">Status</th>
		
		<th class="sorting" role="columnheader" tabindex="0" aria-controls="table-2" rowspan="1" colspan="1" aria-label="Status" style="width: 301px;">Actions</th></tr>
	</thead>
	
	

		
		</table>
		<!--
		<div class="row"><div class="col-xs-6 col-left"><div class="dataTables_info" id="table-2_info">Showing 1 to 8 of 12 entries</div></div><div class="col-xs-6 col-right"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination pagination-sm"><li class="prev disabled"><a href="#"><i class="entypo-left-open"></i></a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li class="next"><a href="#"><i class="entypo-right-open"></i></a></li></ul></div></div></div></div>-->