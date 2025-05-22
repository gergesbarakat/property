<?php echo e(Form::open(['route' => ['unit.store', $property_id], 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name')])); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('bedroom', __('Bedroom'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('bedroom', null, ['class' => 'form-control', 'placeholder' => __('Enter number of bedroom')])); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('kitchen', __('Kitchen'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('kitchen', null, ['class' => 'form-control', 'placeholder' => __('Enter number of kitchen')])); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('baths', __('Bath'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('baths', null, ['class' => 'form-control', 'placeholder' => __('Enter number of bath')])); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('Unit Price', __('Unit Price'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('rent', null, ['class' => 'form-control', 'placeholder' => __('Enter unit price')])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('installment_type', __('Installment Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('rent_type', $rentTypes, null, ['class' => 'form-control hidesearch', 'id' => 'rent_type'])); ?>

        </div>
        <div class="form-group  col-md-12 rent_type monthly ">
            <?php echo e(Form::label('installment_duration', __('Installment Duration'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('rent_duration', null, ['class' => 'form-control', 'placeholder' => __('Enter day of month between 1 to 30')])); ?>

        </div>
        <div class="form-group  col-md-12 rent_type yearly d-none">
            <?php echo e(Form::label('installment_duration', __('Installment Duration'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('rent_duration', null, ['class' => 'form-control', 'placeholder' => __('Enter month of year between 1 to 12'), 'disabled'])); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('start_date', null, ['class' => 'form-control', 'disabled'])); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('end_date', null, ['class' => 'form-control', 'disabled'])); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('payment_due_date', __('Payment Due Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('payment_due_date', null, ['class' => 'form-control', 'disabled'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('deposit_type', __('Deposit Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('deposit_type', $types, null, ['class' => 'form-control hidesearch'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('deposit_amount', __('Deposit Amount'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('deposit_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter deposit amount')])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('late_fee_type', __('Late Fee Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('late_fee_type', $types, null, ['class' => 'form-control hidesearch'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('late_fee_amount', __('Late Fee Amount'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('late_fee_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter late fee amount')])); ?>

        </div>
        
        <div class="form-group col-md-12">
            <?php echo e(Form::label('notes', __('Notes'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter notes')])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <?php echo e(Form::submit(__('Create'), ['class' => 'btn btn-primary btn-rounded'])); ?>

</div>
<?php echo e(Form::close()); ?>

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
<?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/unit/create.blade.php ENDPATH**/ ?>