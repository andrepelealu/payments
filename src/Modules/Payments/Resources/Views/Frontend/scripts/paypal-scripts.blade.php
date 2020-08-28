<script>
    var csrf_token = '{!! csrf_token() !!}';
    paypal.Buttons({
            createOrder: function (data, actions) {
                return $.ajax('{{ route('checkout.payment.submit', $object->reference) }}', {
                    method: 'post',
                    headers: {
                        'x-csrf-token': '{!! csrf_token() !!}'
                    }
                }).then(function (data) {
                    if(data.error) {
                        $('#card-errors').html(data.error.message);
                        return false;
                    }
                    return data.result.id;
                });
            },
            onApprove: function (data) {
                return $.ajax('/payment/' + data.orderID + '/capture', {
                    headers: {
                        'content-type': 'application/json'
                    },
                }).then(function (data) {
                    
                    window.location = data.url;
                });
            },
        }
    ).render('#paypal-button-container');
</script>
