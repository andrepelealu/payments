@section('metaOther')
    <script>
        window.onAmazonLoginReady = function () {
            amazon.Login.setClientId('{{ $paymentMethod->details->client_id }}');
        };
    </script>
    @if(!request()->has('referenceId') && !request()->has('access_token'))
    <script src='https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js'></script>
    @endif
@stop


<div class="card payment-card" id="amazon-form">
    <div class="card-divider" style="display:block;">
        <div class="row">
            <div class="small-12 medium-6 columns">
                Amazon Pay Payment
            </div>
            <div class="small-12 medium-6 columns text-right">
                Total: {{ currency_format($object->total) }}
            </div>
        </div>
    </div>
    <div class="card-section">
        <div class="row">
            <div class="small-12 columns text-center">
                @if(!request()->has('referenceId') && !request()->has('access_token'))
                    <div id="AmazonPayButton"></div>
                @else
                    <div id="walletWidgetDiv"></div>
                    <div id="amazon-logout">LOGOUT</div>
                @endif
                <div id="card-errors" role="alert"></div>
            </div>
        </div>
    </div>
</div>
