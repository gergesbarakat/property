{{ Form::model($unit, ['route' => ['unit.update', $property_id, $unit->id], 'method' => 'PUT']) }}

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('bedroom', __('Bedroom'), ['class' => 'form-label']) }}
            {{ Form::number('bedroom', null, ['class' => 'form-control', 'placeholder' => __('Enter number of bedroom')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('kitchen', __('Kitchen'), ['class' => 'form-label']) }}
            {{ Form::number('kitchen', null, ['class' => 'form-control', 'placeholder' => __('Enter number of kitchen')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('baths', __('Bath'), ['class' => 'form-label']) }}
            {{ Form::number('baths', null, ['class' => 'form-control', 'placeholder' => __('Enter number of bath')]) }}
        </div>

        {{-- Commented-out fields from your create form have been preserved. --}}
        {{-- <div class="form-group  col-md-6">
            {{ Form::label('Price', __('Price'), ['class' => 'form-label']) }}
            {{ Form::number('rent', null, ['class' => 'form-control', 'placeholder' => __('Enter unit rent')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('rent_type', __('Rent Type'), ['class' => 'form-label']) }}
            {{ Form::select('rent_type', $rentTypes, null, ['class' => 'form-control hidesearch', 'id' => 'rent_type']) }}
        </div>
        ... etc. ...
        --}}

        <div class="form-group col-md-12">
            {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
            {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter notes')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{-- Submit button text changed to "Update" --}}
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded']) }}
</div>
{{ Form::close() }}

{{-- The JavaScript for dynamic rent types is preserved. --}}
<script>
    $('#rent_type').on('change', function() {
        "use strict";
        var type = this.value;
        $('.rent_type').addClass('d-none')
        $('.' + type).removeClass('d-none')

        var input1 = $('.rent_type').find('input');
        input1.prop('disabled', true);
        var input2 = $('.' + type).find('input');
        input2.prop('disabled', false);
    });
</script>
