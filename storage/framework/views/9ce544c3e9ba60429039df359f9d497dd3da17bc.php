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