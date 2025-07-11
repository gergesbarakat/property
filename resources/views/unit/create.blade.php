{{ Form::open(['route' => ['unit.store', $property_id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name'), 'required']) }}
        </div>
        <div class="form-group col-md-3">
            {{ Form::label('bedroom', __('Bedroom'), ['class' => 'form-label']) }}
            {{ Form::number('bedroom', null, ['class' => 'form-control', 'placeholder' => __('e.g. 2'), 'required']) }}
        </div>
        <div class="form-group col-md-3">
            {{ Form::label('baths', __('Baths'), ['class' => 'form-label']) }}
            {{ Form::number('baths', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1'), 'required']) }}
        </div>
        <div class="form-group col-md-3">
            {{ Form::label('kitchen', __('Kitchen'), ['class' => 'form-label']) }}
            {{ Form::number('kitchen', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1'), 'required']) }}
        </div>
        {{-- ✅ NEW: Added Unit Size form field --}}
        <div class="form-group col-md-3">
            {{ Form::label('unit_size', __('Unit Size (Sq. Ft.)'), ['class' => 'form-label']) }}
            {{ Form::number('unit_size', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1200')]) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
            {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter notes')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded']) }}
</div>
{{ Form::close() }}
