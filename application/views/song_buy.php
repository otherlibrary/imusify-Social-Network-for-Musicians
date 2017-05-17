<form action="<?php echo base_url()."stripeoperations/trackpayment"; ?>" method="POST">
	<script
	src="https://checkout.stripe.com/checkout.js" class="stripe-button"
	data-key="pk_test_ar32yaPnAs5OZjkS4Q0TwXvO"
	data-amount="<?php echo isset($total) ? $total : 0;  ?>"
	data-name="<?php echo 'Imusify'; ?>"
	data-description="<?php echo isset($tracktitle) ? $tracktitle : "Track"; ?>"
	data-image="/128x128.png"
	data-locale="auto">
</script>
</form>
<script
	src="https://checkout.stripe.com/checkout.js">
<script type="text/javascript">
$(document).ready(function() {
$('#sendPledgeBtn').click(function(){
      var token = function(res){
        var $input = $('<input type=hidden name=stripeToken />').val(res.id);
        var tokenId = $input.val();
        var email = res.email;

        setTimeout(function(){
          $.ajax({
            url:'http://www.webdrumbeat.com/snoopcaller/stripe/charg.php',
            cache: false,
            data:{ email : email, token:tokenId },
            type:'POST'
          })
          .done(function(data){
            // If Payment Success 
            $('#sendPledgeBtn').html('Thank You').addClass('disabled');
          })
          .error(function(){
            $('#sendPledgeBtn').html('Error, Unable to Process Payment').addClass('disabled');
          });
        },500);

        $('form:first-child').append($input).submit();
      };

      StripeCheckout.open({
        key:         'pk_test_X41GM5cT9TejICAXyx77Gviy', // Your Key
        address:     false,
        amount:      1000,
        currency:    'usd',
        name:        'Canted Pictures',
        description: 'Donation',
        panelLabel:  'Checkout',
        token:       token
      });
      return false;
});
});
</script>