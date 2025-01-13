let isProcessing = false;
let inputTimeout = null;

// อัพเดทวันที่และเวลาแบบ Real-time
function updateDateTime() {
    const now = new Date();
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    };
    document.getElementById('current-date').textContent = 
        now.toLocaleString('th-TH', options);
}

// ฟังก์ชันจัดการการส่ง form barcode
function handleBarcodeSubmit(event) {
    event.preventDefault();
    const barcodeInput = document.getElementById('barcode-input');
    const barcodeValue = barcodeInput.value.trim();
    
    if(barcodeValue && !isProcessing) {
        addToCartByBarcode(barcodeValue);
    }
    
    barcodeInput.value = '';
    barcodeInput.focus();
    return false;
}

// เพิ่มสินค้าลงตะกร้าด้วยบาร์โค้ด
function addToCartByBarcode(productId) {
    if(isProcessing) return;
    isProcessing = true;

    $.ajax({
        url: 'check_product.php',
        type: 'POST',
        data: { p_id: productId },
        success: function(response) {
            if(response.trim() === 'found') {
                $.ajax({
                    url: 'update_cart_qty.php',
                    type: 'POST',
                    data: {
                        p_id: productId,
                        qty: 1,
                        add_new: true
                    },
                    success: function(updateResponse) {
                        reloadCartTable();
                        setTimeout(() => {
                            bindCheckoutEnterKey();
                        }, 100);
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการเพิ่มสินค้า');
                    },
                    complete: function() {
                        isProcessing = false;
                    }
                });
            } else {
                alert('ไม่พบสินค้าหรือบาร์โค้ดไม่ถูกต้อง');
                isProcessing = false;
            }
        },
        error: function() {
            alert('เกิดข้อผิดพลาดในการตรวจสอบสินค้า');
            isProcessing = false;
        }
    });
}

// โหลดตารางสินค้าใหม่
function reloadCartTable() {
    location.reload();
}

// Setup event handlers
function setupEventHandlers() {
    $('.discount-input').on('input', function() {
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
        
        const input = $(this);
        updatePrice(input.closest('tr').find('.update-qty'));
        
        inputTimeout = setTimeout(() => {
            if (!input.is(':focus')) return;
            $('#barcode-input').focus();
        }, 1500);
    });
    
    $('.update-qty').on('input', function() {
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
        
        const input = $(this);
        updatePrice(this);
        
        inputTimeout = setTimeout(() => {
            if (!input.is(':focus')) return;
            $('#barcode-input').focus();
        }, 1500);
    });

    $('.discount-input, .update-qty').on('blur', function() {
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
    });
}

// อัพเดทราคาสินค้า
function updatePrice(input) {
    var tr = $(input).closest('tr');
    var p_id = $(input).data('id');
    var qty = parseInt($(input).val());
    var price = parseFloat($(input).data('price'));
    var discount = parseFloat(tr.find('.discount-input').val()) || 0;
    
    var subtotal = price * qty;
    var total_after_discount = subtotal - discount;
    
    if (total_after_discount < 0) {
        total_after_discount = 0;
        tr.find('.discount-input').val(subtotal);
        discount = subtotal;
    }

    tr.find('.sum-' + p_id).text(total_after_discount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));

    var cart_total = 0;
    var total_discount = 0;
    
    $('.update-qty').each(function() {
        var item_tr = $(this).closest('tr');
        var item_qty = parseInt($(this).val());
        var item_price = parseFloat($(this).data('price'));
        var item_discount = parseFloat(item_tr.find('.discount-input').val()) || 0;
        var item_total = (item_price * item_qty) - item_discount;
        
        if (item_total < 0) item_total = 0;
        cart_total += item_total;
        total_discount += item_discount;
    });

    $('#total-amount').text(cart_total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));

    $('#total-discount').text(total_discount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));

    $.ajax({
        url: 'update_cart_qty.php',
        type: 'POST',
        data: {
            p_id: p_id,
            qty: qty,
            discount: discount
        },
        success: function(response) {
            console.log('อัปเดตแล้ว:', response);
        }
    });
}

// ลดจำนวนสินค้า
function decrementQuantity(button) {
    var p_id = $(button).data('id');
    var qtyInput = button.closest('tr').querySelector('.update-qty');
    var currentQty = parseInt(qtyInput.value);
    
    if (currentQty > 1) {
        qtyInput.value = currentQty - 1;
        updatePrice(qtyInput);
        
        $.ajax({
            url: 'update_cart_qty.php',
            type: 'POST',
            data: {
                p_id: p_id,
                qty: currentQty - 1
            }
        });
    } else {
        alert('ไม่สามารถลดจำนวนสินค้าได้ เนื่องจากต้องมีอย่างน้อย 1 ชิ้น');
    }
    $('#barcode-input').focus();
}

// เพิ่มจำนวนสินค้า
function incrementQuantity(button) {
    var p_id = $(button).data('id');
    var maxQty = parseInt($(button).data('max'));
    var qtyInput = button.closest('tr').querySelector('.update-qty');
    var currentQty = parseInt(qtyInput.value);
    
    if (currentQty < maxQty) {
        qtyInput.value = currentQty + 1;
        updatePrice(qtyInput);
        
        $.ajax({
            url: 'update_cart_qty.php',
            type: 'POST',
            data: {
                p_id: p_id,
                qty: currentQty + 1
            }
        });
    } else {
        alert('ไม่สามารถเพิ่มจำนวนได้ เนื่องจากเกินจำนวนในสต็อก');
    }
    $('#barcode-input').focus();
}

// แสดงหน้าคิดเงิน
function showCheckout() {
    const currentTotal = $('#total-amount').text().trim();
    $('#total_amount').val('฿' + currentTotal);
    
    $('#checkout-overlay').fadeIn(function() {
        $('#pay_amount2').val('').focus();
        $('#change_amount').val('');
    });
}

// ซ่อนหน้าคิดเงิน
function hideCheckout() {
    $('#checkout-overlay').fadeOut();
    $('#barcode-input').focus();
}

// คำนวณเงินทอน
function calculateChange() {
    var total = parseFloat(document.getElementById('total_amount').value.replace(/[^0-9.]/g, ''));
    var paid = parseFloat(document.getElementById('pay_amount2').value) || 0;
    var change = paid - total;
    
    if (!isNaN(change)) {
        document.getElementById('change_amount').value = '฿' + change.toFixed(2);
    } else {
        document.getElementById('change_amount').value = '฿0.00';
    }
}

// เคลียร์ตารางสินค้า
function clearCartTable() {
    $('.table tbody').empty();
    $('#total-amount').text('0.00');
    hideCheckout();
    $('#pay_amount2').val('');
    $('#change_amount').val('');
    $('#barcode-input').focus();
}

// ประมวลผลการชำระเงิน
function processPayment() {
    var pay_amount = $('#pay_amount2').val();
    var total_amount = $('#total_amount').val().replace(/[^0-9.]/g, '');
    
    if(!pay_amount) {
        alert('กรุณาระบุจำนวนเงินที่รับ');
        return;
    }
    
    if(parseFloat(pay_amount) < parseFloat(total_amount)) {
        alert('จำนวนเงินไม่พอ');
        return;
    }
    
    $.ajax({
        url: 'saveorder_a.php',
        type: 'POST',
        data: {
            pay_amount: total_amount,
            pay_amount2: pay_amount,
            mem_id: 1 // ต้องแก้ไขให้ตรงกับ ID สมาชิกที่ login
        },
        success: function(response) {
            clearCartTable();
            
            $.ajax({
                url: 'clear_cart_session.php',
                type: 'POST',
                success: function() {
                    console.log('Cart session cleared');
                    location.reload();
                }
            });
        }
    });
}

// Event Listeners เมื่อโหลดหน้า
$(document).ready(function() {
    // อัพเดทวันที่และเวลา
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Event listener สำหรับช่องบาร์โค้ด
    $('#barcode-input').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            let barcodeValue = $(this).val().trim();

            if(barcodeValue === '') {
                if($('.table tbody tr').length > 0) {
                    showCheckout();
                }
            } else {
                addToCartByBarcode(barcodeValue);
            }
            $(this).val('').focus();
        }
    });

    // Setup event handlers
    setupEventHandlers();
    
    // โฟกัสที่ช่องบาร์โค้ด
    $('#barcode-input').focus();
    
    // ป้องกันการ submit form
    $('.btn-success').on('click', function(e) {
        e.preventDefault();
        return false;
    });
});

// Event listener สำหรับปุ่ม ESC
$(document).keydown(function(e) {
    if (e.key === "Escape") {
        hideCheckout();
    }
});