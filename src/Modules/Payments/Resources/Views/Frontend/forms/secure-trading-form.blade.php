{!! Form::open(['route' => ['checkout.payment.submit', $object->reference], 'id' => 'stpayment']) !!}

<div class="card payment-card" id="secure-trading-form">
    <div class="card-divider" style="display:block;">
        <div class="row">
            <div class="small-12 medium-6 columns">
                Card Payment
            </div>
            <div class="small-12 medium-6 columns text-right">
                Total: {{ currency_format($object->total) }}
            </div>
        </div>
    </div>
    <div class="card-section">
        <div class="row">
            <div class="small-12 columns">
                <div id="stmessage" role="alert"></div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <div class="st-applepay-button"></div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label>Card Number</label><input data-st-field="pan" size="20" maxlength="20" type="text" autocomplete="off" required/>
            </div>
        </div>
        <div class="row">
            <div class="small-12 medium-6 columns"><label>Expiration (MM/YYYY)</label>
                <div class="row">
                    <div class="small-12 medium-6 columns"><input data-st-field="expirymonth" size="2" maxlength="2" type="text" autocomplete="off" required/></div>
                    <div class="small-12 medium-6 columns"><input data-st-field="expiryyear" size="4" maxlength="4" type="text" autocomplete="off" required/></div>
                </div>
            </div>
            <div class="small-12 medium-6 columns">
                <label>CVC</label><input data-st-field="securitycode" size="4" type="text" autocomplete="off" required/>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                {!! Form::submit('Submit Payment', ['class' => 'button']) !!}
            </div>
        </div>
    </div>
</div>


{!! Form::close() !!}