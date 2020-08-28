<script src="https://webservices.securetrading.net/js/st.js"></script>
<script>
    new SecureTrading.Standard({
        sitereference: "{{ $paymentMethod->details->sitereference }}",
        locale: "en_gb",
        messageId: "stmessage",
        formId: "stpayment",
        submitFormCallback: function (responseObj) {
            var cachetoken = responseObj['response'][0]['cachetoken'];
            var form = document.getElementById('stpayment');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_token');
            hiddenInput.setAttribute('value', cachetoken);
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
</script>
@php
    $transaction = $object->transactions()->orderBy('id', 'desc')->first();
@endphp
<script>
    new SecureTrading.ApplePay({
        sitereference: "{{ $paymentMethod->details->sitereference }}",
        messageId: "stmessage",
        formId: "stpayment",
        paymentRequest: {
            "total": {
                "label": "now",
                "amount": "{{ currency_format_raw($object->total) }}"
            },
            "countryCode": "{{ $object->country_code }}",
            "currencyCode": "{{ $object->currency }}",
            "merchantCapabilities": ["supports3DS", "supportsCredit", "supportsDebit"],
            supportedNetworks: ["visa", "masterCard"]
        },
        "merchantId": "{{ $paymentMethod->details->merchant_id }}",
        "merchantUrl": "{{ route('checkout.payment.submit', $object->reference) }}",
        "successUrl": "{{ route('payment.complete', $transaction->reference) }}"
    });
</script>