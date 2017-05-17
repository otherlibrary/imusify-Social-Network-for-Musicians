<form id="checkout" method="post" action="/checkout">
  <div id="payment-form"></div>
  <input type="submit" value="Pay $10">
</form>

<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
    //  braintree.setup("<?php echo $client_token; ?>", "<integration>", options);

      braintree.setup("<?php echo $client_token; ?>", "dropin", {
  		container: "payment-form"
	});

</script>