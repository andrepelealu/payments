@if(!request()->has('referenceId') && !request()->has('access_token'))
    <script type="text/javascript">
        var authRequest;
        var accessToken;
        var errorHandler;
        OffAmazonPayments.Button("AmazonPayButton", "{{ $paymentMethod->details->merchant_id }}", {
            type: "PwA",
            color: "Gold",
            size: "large",
            language: "en-UK",
            authorization: function () {
                loginOptions = {scope: "profile payments:widget", popup: true};
                authRequest = amazon.Login.authorize(loginOptions, function (response) {
                    accessToken = response.access_token;
                });
            },
            onSignIn: function (orderReference) {
                var referenceId = orderReference.getAmazonOrderReferenceId();
                if (!referenceId) {
                    errorHandler(new Error('referenceId missing'));
                } else {
                    window.location = "{{ url()->current() }}" + '?referenceId=' +
                        orderReference.getAmazonOrderReferenceId() +
                        "&access_token=" + accessToken;
                }
            },
            onError: errorHandler || function (error) {
                // your error handling code
                console.log(error);
            }
        });
    </script>
@else
    <script async="async"
            src='https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js'>
    </script>
    <script type='text/javascript'>
        $('#walletWidgetDiv').css({height: "300px"});
        $('#amazon-logout').on('click', function () {
            console.log('here');
            amazon.Login.logout();
        });
    </script>
    <script type="text/javascript">
        window.onAmazonPaymentsReady = function() {
            walletWidget = new OffAmazonPayments.Widgets.Wallet({
                sellerId: '{{ $paymentMethod->details->merchant_id }}',
                amazonOrderReferenceId: '{{ request()->input('referenceId') }}', //the one you created before, most likely in the AddressBook widget
                onPaymentSelect: function(orderReference) {
                    // Replace this code with the action that you want to perform
                    // after the payment method is selected.
alert("selected");
                    // Ideally, this would enable the next action for the buyer
                    // including either a "Continue" or "Place Order" button.
                },
                design: {
                    designMode: 'responsive'
                },
                onError: function(error) {
                    // Your error handling code.
                    // During development you can use the following
                    // code to view error messages:
                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                    // See "Handling Errors" for more information.
                }
            });
            walletWidget.setPresentmentCurrency("GBP"); // ISO-4217 currency code, merchant is expected to enter valid list of currency supported by Amazon Pay.
            walletWidget.bind("walletWidgetDiv");
        };
    </script>
@endif