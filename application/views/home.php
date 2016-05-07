<?php  		if(isset($this->session->userdata('user')->id) && $this->session->userdata('user')->id > 0)
{
	$loggedin = "true";
	$userIdJs = $this->session->userdata('user')->id;
}
else
{
	$loggedin = "false";
	$userIdJs = 0;
} 

if($this->session->userdata('notification') != "")
{
	$notification = $this->session->userdata('notification');
	$this->session->unset_userdata('notification');
}
else
{
	$notification = NULL;
} 
/*membership*/
$avail_sapce = 0;
if(isset($this->session->userdata('user')->avail_space) && ($this->session->userdata('user')->avail_space > 0 || $this->session->userdata('user')->avail_space  == "-1"))
{
	$avail_sapce = $this->session->userdata('user')->avail_space;
}			
/*membership ends*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--        andy-->
	<meta http-equiv="pragma" content="no-cache" /> 
        <meta http-equiv="expires" content="-1" />
        <meta http-equiv="cache-control" content="no-cache"/>

	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="<?php echo asset_url(); ?>images/favicon.ico" type="image/x-icon">
	<script type="text/javascript">
		var config={
			loggedIn: <?php print $loggedin; ?>,
			notification: "<?php print ($notification != NULL) ? $notification : NULL ;?>",
			siteUrl:'<?php print base_url();?>',
			AssetUrl:'<?php print base_url();?>assets/',
			ViewUrl:'<?php print base_url();?>assets/views/',
			history_redirect_url:"<?php print (!isset($redirectURL))?base_url():$redirectURL;?>",
			current_tm:"<?php print (isset($current_tm))?$current_tm:'';?>",
			userIdJs:"<?php print (isset($userIdJs))?$userIdJs:'';?>",
			controller:'<?php  echo $this->router->fetch_class(); ?>',
			method:'<?php  echo $this->router->fetch_method(); ?>',
			sitename:'<?php  echo SITE_NM; ?>',
			stripe_public_key:'<?php  echo STRIPE_PUBLIC_API_KEY; ?>',
			max_upload_file_size:'<?php  echo MAX_UPLOAD_FILE_SIZE_ALLOWED; ?>',

		};

		paceOptions = {
			ajax: {ignoreURLs: ['unread','api/message']},
			trackMethods:['GET','POST'],
		}

	</script>
	<?php print put_headers();?>

	<?php if(isset($this->session->userdata('user')->avail_space) && ($this->session->userdata('user')->avail_space > 0 || $this->session->userdata('user')->avail_space  == "-1"))
{ ?>

	<script src="https://checkout.stripe.com/checkout.js"></script>

	<?php } ?>

	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" style="display:none;height:0;">
		<filter id="blur">
			<feGaussianBlur stdDeviation="40"></feGaussianBlur>
		</filter>
	</svg>
</head>
<body class="">

<div class="startup_progress" id="startup_progress"></div>

	<div id="loading_cont" >
		<div class="progressbar" data-perc="50">
			<div class="bar color5"><span></span></div>
			<div class="label"><span></span></div>
		</div>
	</div>
	<!-- mCustomScrollbar -->
	<div id="main" class="u-fancy-scrollbar">

		<div class="container-fluid">
			<div class="row">
				<?php print $data;?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var avail_space = <?php print (isset($avail_sapce))?$avail_sapce:"";?>;
		$("body").data("avail_space",avail_space);
	</script>	

	<script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
/*UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/opfMnqTVeqSzaFSnKVrVQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

//
// UserVoice Javascript SDK developer documentation:
// https://www.uservoice.com/o/javascript-sdk
//

// Set colors
UserVoice.push(['set', {
  accent_color: '#448dd6',
  trigger_color: 'white',
  trigger_background_color: 'rgba(46, 49, 51, 0.6)'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {
  //email:      'john.doe@example.com', // User’s email address
  //name:       'John Doe', // User’s real name
  //created_at: 1364406966, // Unix timestamp for the date the user signed up
  //id:         123, // Optional: Unique id of the user (if set, this should not change)
  //type:       'Owner', // Optional: segment your users by type
  //account: {
  //  id:           123, // Optional: associate multiple users with a single account
  //  name:         'Acme, Co.', // Account name
  //  created_at:   1364406966, // Unix timestamp for the date the account was created
  //  monthly_rate: 9.99, // Decimal; monthly rate of the account
  //  ltv:          1495.00, // Decimal; lifetime value of the account
  //  plan:         'Enhanced' // Plan name for the account
  //}
}]);

// Add default trigger to the bottom-right corner of the window:
UserVoice.push(['addTrigger', {trigger_position: 'bottom-right' }]);

// Or, use your own custom trigger:
//UserVoice.push(['addTrigger', '#id']);

// Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
UserVoice.push(['autoprompt', {}]);*/
</script>

</body>
</html>