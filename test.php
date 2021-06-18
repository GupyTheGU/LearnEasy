<?php
    
    $ClientID = "ASTh7jOIF78sQnOQoPC14mMKhe7tW9iqmC-TaCaBIJPKl0cYmfplPq5ku6R9JaNuswTXhAa4FQ1xufYp";
    $Currency = "MXN";
    $Value = 4642.85;

    $Source = "https://www.paypal.com/sdk/js?client-id=". $ClientID ."&amp;currency=". $Currency;

?>

<html>
    <head>
        <script src=<?php echo $Source ?>></script>
        <script>
            function initPayPalButton() {
                paypal.Buttons({
                    style: {
                        shape: 'pill',
                        color: 'black',
                        layout: 'horizontal',
                        label: 'pay',
                        tagline: false
          
                    },
                    createOrder: function(data, actions) {
                        return actions.order.create({
                        purchase_units: [{"amount":{"currency_code":"MXN","value":<?php echo $Value ?>}}]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                        alert('Transacción completada por ' + details.payer.name.given_name + '!');
                        });
                    },
                    onError: function(err) {
                        console.log(err);
                    }
                }).render('#paypal-button-container');
            }
            initPayPalButton();
        </script>
    </head>
    <body>
        <div id="paypal-button-container"></div>
    </body>
</html>