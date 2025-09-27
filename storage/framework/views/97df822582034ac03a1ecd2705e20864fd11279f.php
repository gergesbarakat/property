<?php echo e(Form::model($unit, ['route' => ['unit.update', [$property_id, $unit->id]], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name'), 'required'])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('bedroom', __('Bedroom'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('bedroom', null, ['class' => 'form-control', 'placeholder' => __('e.g. 2')])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('baths', __('Baths'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('baths', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1')])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('kitchen', __('Kitchen'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('kitchen', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1')])); ?>

        </div>
        <div class="form-group col-md-3">
            <?php echo e(Form::label('unit_size', __('Unit Size (Sq. Ft.)'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('unit_size', null, ['class' => 'form-control', 'placeholder' => __('e.g. 1200')])); ?>

        </div>

        <div class="form-group col-md-4">
            <?php echo e(Form::label('floor', __('Floor'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('floor', null, ['class' => 'form-control', 'placeholder' => __('e.g. 3')])); ?>

        </div>
        <div class="form-group col-md-4">
            <?php echo e(Form::label('building', __('Building'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('building', null, ['class' => 'form-control', 'placeholder' => __('e.g. Building A')])); ?>

        </div>
        <div class="form-group col-md-4">
            <?php echo e(Form::label('location', __('Location'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('e.g. North Wing')])); ?>

        </div>

        <div class="form-group col-md-12">
            <?php echo e(Form::label('notes', __('Notes'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter notes')])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded'])); ?>

</div>
<?php echo e(Form::close()); ?>

<?php /**PATH F:\JOWEB\property\resources\views/unit/edit.blade.php ENDPATH**/ ?>