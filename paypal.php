<script
   src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_client; ?>&currency=MXN" data-order-id="omar-2VW94544JM6797511">
 </script>

 <div id="paypal-button-container"></div>

 <script>
    paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '<?php echo $gtotal; ?>'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      Swal.fire({
          type: 'success',
          title: 'no cierre la ventana, finalizando pago',
          showConfirmButton: false
      });
      return actions.order.capture().then(function(details) {
        $.ajax({
          url: "paypal-transaction-complete.php",
          type: "POST",
          data: {
            "id":details.id,
            "mail":details.payer.email_address,
            "estatus":details.status,
            "idx":<?php echo $idpedido; ?>
          },
          success: function( response ) {
            window.location.href="https://www.tic-shop.com.mx/estado_pedido.php?idpedido="+$idpedido;
          }
        });
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
  }).render('#paypal-button-container');
  //This function displays Smart Payment Buttons on your web page.

 </script>
