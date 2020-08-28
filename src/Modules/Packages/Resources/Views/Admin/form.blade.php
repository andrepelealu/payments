<div class="row">
    <div class="small-12 medium-6 columns">
        <div class="content-block">
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('active', 'Active?', ['class' => $errors->first('active', 'is-invalid-label')]) !!}
                    {!! Form::select('active', ['1' => 'Published', '0' => 'Hidden'], null, ['class' => $errors->first('active', 'is-invalid-input')])!!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('name', 'Name', ['class' => $errors->first('name', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::text('name', null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::text('name', null, ['class' => $errors->first('name', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('name', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('description', 'Description', ['class' => $errors->first('description', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::textarea('description', null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::text('description', null, ['class' => 'advanced-editor ' . $errors->first('description', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('description', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('frequency', 'Frequency', ['class' => $errors->first('frequency', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::select('frequency', ['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year'], null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::select('frequency', ['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year'], null, ['class' => $errors->first('frequency', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('frequency', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('frequency_interval', 'Frequency Interval', ['class' => $errors->first('frequency_interval', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::number('frequency_interval', null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::number('frequency_interval', null, ['step' => 1, 'class' => $errors->first('frequency_interval', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('frequency_interval', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('price', 'Price', ['class' => $errors->first('price', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::number('price', null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::number('price', null, ['step' => 0.01, 'class' => $errors->first('price', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('price', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::label('currency', 'Currency', ['class' => $errors->first('currency', 'is-invalid-label')]) !!}
                    @if(isset($package))
                        {!! Form::select('currency', $currencies, null, ['disabled' => 'disabled']) !!}
                    @else
                        {!! Form::select('currency', $currencies, null, ['class' => $errors->first('currency', 'is-invalid-input')]) !!}
                    @endif
                    {!! $errors->first('currency', '<span class="form-error is-visible">:message</span>' ) !!}
                </div>
            </div>
        </div>
        <div class="button-block text-right">
            <div class="row">
                <div class="small-12 columns">
                    {!! Form::submit($submit, ['class' => 'button success']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
