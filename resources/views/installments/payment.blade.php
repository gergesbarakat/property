{{-- This form is loaded into the modal via AJAX --}}
<form action="{{ route('installment.payment.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="installment_id" value="{{ $installment->id }}">
        <input type="hidden" name="amount" value="{{ $installment->amount }}">

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
                max="{{ $installment->amount }}">
        </div>

        <div class="mb-3">
            <label for="modal_payment_date" class="form-label">Payment Date</label>
            <input type="date" class="form-control" name="payment_date" value="{{ now()->format('Y-m-d') }}"
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

{{-- This script controls the partial payment field within the modal --}}
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
