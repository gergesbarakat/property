{{-- This form is loaded into the modal via AJAX --}}
{{ Form::model($installment, ['route' => ['installment.notes.update', $installment->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
        {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('Enter any relevant notes for this installment')]) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
