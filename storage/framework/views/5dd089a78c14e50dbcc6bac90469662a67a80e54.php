<div class="modal-body">
    <div class="product-card">
        <div class="row">
            <div class="col-12">
                <div class="detail-group">
                    <h6><?php echo e(__('Expense Title')); ?></h6>
                    <p class="mb-20"><?php echo e($expense->title); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Expense Number')); ?></h6>
                    <p class="mb-20"><?php echo e(expensePrefix().$expense->expense_id); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Expense Type')); ?></h6>
                    <p class="mb-20"><?php echo e(!empty($expense->types)?$expense->types->title:'-'); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Property')); ?></h6>
                    <p class="mb-20"> <?php echo e(!empty($expense->properties)?$expense->properties->name:'-'); ?> </p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Unit')); ?></h6>
                    <p class="mb-20"><?php echo e(!empty($expense->units)?$expense->units->name:'-'); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Date')); ?></h6>
                    <p class="mb-20"> <?php echo e(dateFormat($expense->date)); ?> </p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Amount')); ?></h6>
                    <p class="mb-20"><?php echo e(priceFormat($expense->amount)); ?></p>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-group">
                    <h6><?php echo e(__('Receipt')); ?></h6>
                    <p class="mb-20">
                        <?php if(!empty($expense->receipt)): ?>
                            <a href="<?php echo e(asset(Storage::url('upload/receipt')).'/'.$expense->receipt); ?>" download="download"><i data-feather="download"></i></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="col-12">
                <div class="detail-group">
                    <h6><?php echo e(__('Notes')); ?></h6>
                    <p class="mb-20"><?php echo e($expense->notes); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\girgi\Desktop\JOWEB\property\resources\views/expense/show.blade.php ENDPATH**/ ?>