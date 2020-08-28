<div class="content-block">
    <p class="content-block-title">Payment Method Details</p>
    <div class="content">
        <div class="row">
            <div class="small-12 columns">
                {!! Form::label('active', 'Active?', ['class' => $errors->first('active', 'is-invalid-label')]) !!}
                {!! Form::select('active', ['1' => 'Published', '0' => 'Hidden'], null, ['class' => $errors->first('active', 'is-invalid-input')])!!}
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                {!! Form::label('name', 'Name', ['class' => $errors->first('name', 'is-invalid-label')]) !!}
                {!! Form::text('name', null, ['class' => $errors->first('name', 'is-invalid-input')]) !!}
                {!! $errors->first('name', '<span class="form-error is-visible">:message</span>' ) !!}
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                {!! Form::label('image', 'Image', ['class' => $errors->first('image', 'is-invalid-label')]) !!}
                <div class="input-group">
                    {!! Form::text('image', null, ['class' => 'input-group-field ' . $errors->first('image', 'is-invalid-input')]) !!}
                    <div class="input-group-button">
                        <input type="submit" class="button moxie-image-browse" data-moxie-field="image" value="Browse">
                    </div>
                </div>
                {!! $errors->first('image', '<span class="form-error is-visible">:message</span>') !!}
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                {!! Form::label('mode', 'Mode?', ['class' => $errors->first('mode', 'is-invalid-label')]) !!}
                {!! Form::select('mode', ['live' => 'Live', 'test' => 'Test'], null, ['class' => $errors->first('mode', 'is-invalid-input')])!!}
            </div>
        </div>
        @if(isset($paymentmethod->details) && ! empty($paymentmethod->details))
            @foreach($paymentmethod->details as $name => $value)
                <div class="row">
                    <div class="small-12 columns">
                        <label for="">{{ ucwords(str_replace('_', ' ', $name)) }}</label>
                        {!! Form::text('details['.$name.']', $value) !!}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="button-block text-right">
    <div class="row">
        <div class="small-12 columns">
            {!! Form::submit($submit, ['class' => 'button success']) !!}
        </div>
    </div>
</div>