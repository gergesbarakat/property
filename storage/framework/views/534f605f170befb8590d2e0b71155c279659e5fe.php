
<form action="<?php echo e(route('installment.payment.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
        <input type="hidden" name="installment_id" value="<?php echo e($installment->id); ?>">
        <input type="hidden" name="amount" value="<?php echo e($installment->amount); ?>">

        <div class="mb-3">
            <label for="payment_type" class="form-label">Payment Type</label>
            <select name="payment_type" id="payment_type_modal" class="form-control">
                <option value="full">Full Payment</option>
                <option value="partial">Partial Payment</option>
            </select>
        </div>

        <div class="mb-3" id="partial_amount_div_modal" style="display: none;">
            <label for="modal_partial_amount" class="form-label">Partial Amount Paid</label>
            <input type="number" class="form-control" name="partial_amount" step="0.01"
                max="<?php echo e($installment->amount); ?>">
        </div>

        <div class="mb-3">
            <label for="modal_payment_date" class="form-label">Payment Date</label>
            <input type="date" class="form-control" name="payment_date" value="<?php echo e(now()->format('Y-m-d')); ?>"
                required>
        </div>

        <div class="mb-3">
            <label for="modal_receipt" class="form-label">Payment Receipt</label>
            <input type="file" class="form-control" name="receipt" required>
        </div>

        <div class="mb-3">
            <label for="modal_notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Payment</button>
    </div>
</form>


<script>
    $('#payment_type_modal').on('change', function() {
        if ($(this).val() === 'partial') {
            $('#partial_amount_div_modal').slideDown();
            $('#partial_amount_div_modal input').prop('required', true);
        } else {
            $('#partial_amount_div_modal').slideUp();
            $('#partial_amount_div_modal input').prop('required', false).val('');
        }
    });
</script>
<?php /**PATH F:\JOWEB\property\resources\views/installments/payment.blade.php ENDPATH**/ ?>