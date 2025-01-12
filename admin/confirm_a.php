<?php
session_start();
if(!empty($_SESSION['cart'])) {
?>

<div class="container p-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-4">ชำระเงิน</h3>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">ยอดชำระ</label>
                        <input type="text" class="form-control form-control-lg bg-light" 
                               value="฿<?= number_format($total,2) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">รับเงิน</label>
                        <input type="number" name="pay_amount2" id="pay_amount2" 
                               class="form-control form-control-lg" required 
                               min="<?= $total ?>" autofocus>
                        <input type="hidden" name="pay_amount" value="<?= $total ?>">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">เงินทอน</label>
                        <input type="text" id="change_amount" 
                               class="form-control form-control-lg bg-light" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">หมายเหตุ</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            <button type="button" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal">
                                ยกเลิก
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="processPayment()">
                                คิดเงิน
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#pay_amount2').on('input', function() {
        var total = <?= $total ?>;
        var paid = $(this).val();
        var change = paid - total;
        $('#change_amount').val('฿' + change.toFixed(2));
    });
});

function processPayment() {
    var pay_amount = $('#pay_amount2').val();
    if(!pay_amount) {
        alert('กรุณาระบุจำนวนเงินที่รับ');
        return;
    }
    
    $.ajax({
        url: 'saveorder_a.php',
        type: 'POST',
        data: {
            pay_amount: <?= $total ?>,
            pay_amount2: pay_amount,
            mem_id: <?= $_SESSION['mem_id'] ?>
        },
        success: function(response) {
            location.reload();
        }
    });
}
</script>
<?php } ?>