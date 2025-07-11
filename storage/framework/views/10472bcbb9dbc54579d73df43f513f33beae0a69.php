<?php echo e(Form::open(['route' => ['unit.store', $property_id], 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name'), 'required'])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('bedroom', __('Bedroom'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('bedroom', null, ['class' => 'form-control', 'placeholder' => __('e.g. 2'), 'required'])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('baths', __('Baths'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('baths', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1'), 'required'])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('kitchen', __('Kitchen'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('kitchen', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1'), 'required'])); ?>

        </div>
        
        <div class="form-group col-md-3">
            <?php echo e(Form::label('unit_size', __('Unit Size (Sq. Ft.)'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('unit_size', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1200')])); ?>

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

<?php /**PATH E:\JOWEB\property\resources\views/unit/create.blade.php ENDPATH**/ ?>