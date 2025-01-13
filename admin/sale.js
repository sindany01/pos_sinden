// Global Variables
let isProcessing = false;

// จัดการการส่งฟอร์มบาร์โค้ด
function handleBarcodeSubmit(event) {
    event.preventDefault();
    const barcodeInput = document.getElementById('barcode-input');
    const barcodeValue = barcodeInput.value.trim();
    
    if(barcodeValue && !isProcessing) {
        window.location.href = `list_l.php?p_id=${barcodeValue}&act=add`;
    }
    
    barcodeInput.value = '';
    barcodeInput.focus();
    return false;
}

// เพิ่ม/ลดจำนวนสินค้า
function updateQuantity(p_id, qty) {
    $.ajax({
        url: 'update_cart_qty.php',
        type: 'POST',
        data: {
            p_id: p_id,
            qty: qty
        },
        success: function() {
            location.reload();
        }
    });
}

// ลดจำนวนสินค้า
function decrementQuantity(button) {
    const p_id = $(button).data('id');
    const qtyInput = $(button).closest('tr').find('.update-qty');
    const currentQty = parseInt(qtyInput.val());
    
    if (currentQty > 1) {
        updateQuantity(p_id, currentQty - 1);
    } else {
        alert('ไม่สามารถลดจำนวนสินค้าได้ต่ำกว่า 1');
    }
}

// เพิ่มจำนวนสินค้า
function incrementQuantity(button) {
    const p_id = $(button).data('id');
    const maxQty = parseInt($(button).data('max'));
    const qtyInput = $(button).closest('tr').find('.update-qty');
    const currentQty = parseInt(qtyInput.val());
    
    if (currentQty < maxQty) {
        updateQuantity(p_id, currentQty + 1);
    } else {
        alert('สินค้าในสต๊อกไม่เพียงพอ');
    }
}

// ลบสินค้า
function removeItem(p_id) {
    if(confirm('ต้องการลบสินค้านี้ใช่หรือไม่?')) {
        window.location.href = `list_l.php?p_id=${p_id}&act=remove`;
    }
}

// อัพเดทราคา
function updatePrice(input) {
    const tr = $(input).closest('tr');
    const p_id = $(input).data('id');
    const qty = parseInt($(input).val());
    const price = parseFloat($(input).data('price'));
    const discount = parseFloat(tr.find('.discount-input').val()) || 0;
    
    // อัพเดทราคารวมของสินค้า
    const total = (price * qty) - discount;
    tr.find('.sum-' + p_id).text(total.toFixed(2));

    // อัพเดทฐานข้อมูล
    $.ajax({
        url: 'update_cart_qty.php',
        type: 'POST',
        data: {
            p_id: p_id,
            qty: qty,
            discount: discount
        },
        success: function() {
            // คำนวณยอดรวมทั้งหมด
            let cart_total = 0;
            let total_discount = 0;
            
            $('.update-qty').each(function() {
                const item_tr = $(this).closest('tr');
                const item_qty = parseInt($(this).val());
                const item_price = parseFloat($(this).data('price'));
                const item_discount = parseFloat(item_tr.find('.discount-input').val()) || 0;
                
                cart_total += (item_price * item_qty) - item_discount;
                total_discount += item_discount;
            });

            // อัพเดทการแสดงผล
            $('#total-amount').text(cart_total.toFixed(2));
            $('#total-discount').text(total_discount.toFixed(2));
        }
    });
}

// แสดงหน้าชำระเงิน
function showCheckout() {
    const totalAmount = $('#total-amount').text();
    $('#total_amount').val('฿' + totalAmount);
    $('#checkout-overlay').removeClass('d-none');
    $('#pay_amount2').val('').focus();
}

// ซ่อนหน้าชำระเงิน
function hideCheckout() {
    $('#checkout-overlay').addClass('d-none');
    $('#barcode-input').focus();
}

// คำนวณเงินทอน
function calculateChange() {
    const total = parseFloat($('#total_amount').val().replace(/[^0-9.]/g, ''));
    const paid = parseFloat($('#pay_amount2').val()) || 0;
    const change = paid - total;
    
    $('#change_amount').val(change >= 0 ? '฿' + change.toFixed(2) : '฿0.00');
}

// ประมวลผลการชำระเงิน
function processPayment() {
    const payAmount = parseFloat($('#pay_amount2').val());
    const totalAmount = parseFloat($('#total_amount').val().replace(/[^0-9.]/g, ''));
    
    if(!payAmount) {
        alert('กรุณาระบุจำนวนเงินที่รับ');
        return;
    }
    
    if(payAmount < totalAmount) {
        alert('จำนวนเงินไม่เพียงพอ');
        return;
    }
    
    $.ajax({
        url: 'saveorder_a.php',
        type: 'POST',
        data: {
            pay_amount: totalAmount,
            pay_amount2: payAmount,
            mem_id: 1
        },
        success: function() {
            alert('บันทึกรายการเรียบร้อย');
            $.post('clear_cart_session.php', function() {
                location.reload();
            });
        },
        error: function() {
            alert('เกิดข้อผิดพลาดในการบันทึกรายการ');
        }
    });
}

// Event Handlers
$(document).ready(function() {
    // จัดการ input events
    $('.update-qty, .discount-input').on('change', function() {
        updatePrice($(this).closest('tr').find('.update-qty'));
    });
    
    // ESC key handler
    $(document).keydown(function(e) {
        if(e.key === "Escape") {
            hideCheckout();
        }
    });
    
    // Enter key handler สำหรับช่องบาร์โค้ด
    $('#barcode-input').keydown(function(e) {
        if(e.keyCode === 13) {
            e.preventDefault();
            const value = $(this).val().trim();
            
            if(value === '') {
                if($('.table tbody tr').length > 0) {
                    showCheckout();
                }
            } else {
                handleBarcodeSubmit(e);
            }
        }
    });
    
    // โฟกัสที่ช่องบาร์โค้ด
    $('#barcode-input').focus();
});