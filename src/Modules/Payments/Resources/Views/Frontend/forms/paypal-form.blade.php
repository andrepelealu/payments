@section('metaOther')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paymentMethod->details->client_id }}&currency={{ $object->currency }}&disable-funding=credit,card,sepa&commit=true"></script>
@stop

<div class="card payment-card" id="paypal-form">
    <div class="card-divider" style="display:block;">
        <div class="row">
            <div class="small-12 medium-6 columns">
                Paypal Payment
            </div>
            <div class="small-12 medium-6 columns text-right">
                Total: {{ currency_format($object->total) }}
            </div>
        </div>
    </div>
    <div class="card-section">
        <div class="row">
            <div class="small-12 columns text-center">
                {!! Form::label('card-label', 'Please click the button below to pay with paypal.') !!} <br>
                <div id="paypal-button-container"></div>
                <div id="card-errors" role="alert"></div>
            </div>
        </div>
    </div>
</div>
