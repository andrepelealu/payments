@section('metaOther')
    <script src="https://js.stripe.com/v3/"></script>
@stop

{!! Form::open(['route' => ['checkout.payment.submit', $object->reference], 'id' => 'payment-form']) !!}

<div class="card payment-card" id="stripe-form">
    <div class="card-divider" style="display:block;">
        <div class="row">
            <div class="small-12 medium-6 columns">
                Credit / Debit Card Payment
            </div>
            <div class="small-12 medium-6 columns text-right">
                Total: {{ currency_format($object->total) }}
            </div>
        </div>
    </div>
    <div class="card-section">
        {!! Form::label('card-label', 'Enter Credit or Debit card number') !!}
        <div class="row">
            <div class="small-8 columns">
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            <div class="small-4 columns">
                {!! Form::submit('Submit Payment', ['class' => 'button']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
