<?php echo e(Form::open(array('url'=>'type','method'=>'post'))); ?>

<div class="modal-body">
    <div class="form-group ">
        <?php echo e(Form::label('title',__('Title'),array('class'=>'form-label'))); ?>

        <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Invoice / Expense / Maintainance Issue,Type Title')))); ?>

    </div>
    <div class="form-group">
        <?php echo e(Form::label('type', __('Type'),['class'=>'form-label'])); ?>

        <?php echo Form::select('type', $types, null,array('class' => 'form-control hidesearch','required'=>'required')); ?>

    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>



<?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/type/create.blade.php ENDPATH**/ ?>