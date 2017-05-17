<script type="text/javascript">
var response = <?php print json_encode($response); ?>;
console.log(response);
//window.onbeforeunload = function (e) {  
     //alert("d");
	 parent.App.initSuccessSocialLogin(response,<?php print $flag; ?>);  
//};
window.close(); 
</script>