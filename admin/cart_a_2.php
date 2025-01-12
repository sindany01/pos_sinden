<!-- โหลด jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตรวจสอบก่อนเริ่ม session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// เพิ่มการเชื่อมต่อฐานข้อมูล
require_once('../condb.php');

// ตรวจสอบการเชื่อมต่อ
if (!$condb) {
    die("Connection failed: " . mysqli_connect_error());
}

// ส่วนที่เหลือของโค้ดเดิม...

$p_id = isset($_GET['p_id']) ? mysqli_real_escape_string($condb, $_GET['p_id']) : '';
$actdd = mysqli_real_escape_string($condb, 'add');
$act = isset($_GET['act']) ? mysqli_real_escape_string($condb, $_GET['act']) : '';

if($actdd=='add' && !empty($p_id)) {
    if(isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++;
    } else {
        $_SESSION['cart'][$p_id]=1;
    }
}

if($act=='remove' && !empty($p_id)) {
    unset($_SESSION['cart'][$p_id]);
}
?>

<!-- <h4>รายการสั่งซื้อ</h4> -->
<br>
<div class="content">
    <div class="table-container">
            <div class="table-responsive">
                <table border="0" align="center" class="table table-hover table-bordered table-striped">
                    <tbody>
                        <?php
                        $total = 0;
                        if(!empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $p_id=>$qty) {
                                $sql = "SELECT * FROM tbl_product WHERE p_id=$p_id";
                                $query = mysqli_query($condb, $sql);
                                if ($query) {
                                    $row = mysqli_fetch_array($query);
                                    if ($row) {
                                        $sum = $row['p_price'] * $qty;
                                        $total += $sum;
                                        ?>
                                        <tr>
                                                <td class="text-center" style="font-size: 22px; vertical-align: middle;"><?= @$i+=1 ?></td>
                                                <td class="text-center" style="font-size: 22px; vertical-align: middle;"><?= $p_id ?></td>
                                                <td style="font-size: 22px; vertical-align: middle;">
                                                    <?= $row["p_name"] ?>
                                                    <span style="font-size: 18px; color: #666;">สต๊อก <?= $row['p_qty'] ?></span>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <input type="number" 
                                                        name="amount[<?= $p_id ?>]" 
                                                        value="<?= $qty ?>" 
                                                        class="form-control update-qty" 
                                                        min="1" 
                                                        max="<?= $row['p_qty'] ?>"
                                                        data-id="<?= $p_id ?>"
                                                        data-price="<?= $row["p_price"] ?>"
                                                        oninput="updatePrice(this)"
                                                        style="font-size: 22px; height: auto; padding: 10px;"/>
                                                </td>
                                                <td class="text-right price-<?= $p_id ?>" style="font-size: 22px; vertical-align: middle;">
                                                    <?= number_format($row["p_price"],2) ?>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <input type="number"
                                                        name="discount[<?= $p_id ?>]"
                                                        value="<?= isset($_SESSION['discount'][$p_id]) ? $_SESSION['discount'][$p_id] : 0 ?>"
                                                        class="form-control discount-input"
                                                        min="0"
                                                        max="<?= $sum ?>"
                                                        data-id="<?= $p_id ?>"
                                                        style="font-size: 22px; height: auto; padding: 10px;"
                                                        oninput="updatePrice($(this).closest('tr').find('.update-qty'))"/>
                                                </td>
                                                <td class="text-right sum-<?= $p_id ?>" style="font-size: 22px; vertical-align: middle;">
                                                    <?= number_format($sum,2) ?>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <div class="btn-group">
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-lg"
                                                                    onclick="decrementQuantity(this)"
                                                                    data-id="<?= $p_id ?>">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    class="btn btn-success btn-lg"
                                                                    onclick="incrementQuantity(this)"
                                                                    data-id="<?= $p_id ?>"
                                                                    data-max="<?= $row['p_qty'] ?>">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <a href="list_l.php?p_id=<?= $p_id ?>&act=remove" 
                                                    class="btn btn-danger btn-lg">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        <?php } ?>
                    </tbody>
                </table>
        </div>
  </div>
</div>
    <?php
    $total_discount = 0;
    if(!empty($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $p_id=>$qty) {
            if(isset($_SESSION['discount'][$p_id])) {
                $total_discount += $_SESSION['discount'][$p_id];
            }
        }
    }
    ?>
<div class="fixed-bottom-bar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="real-time-date text-left">
                <span id="current-date" style="font-size: 20px; font-weight: bold;"></span>
                <script>
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
                    updateDateTime();
                    setInterval(updateDateTime, 1000);
                </script>
            </div>
            <div class="d-flex justify-content-center align-items-center flex-nowrap col-4">
                <div class="mr-4">
                    <span class="total-label">ส่วนลดรวม:</span>
                    <span class="total-discount" id="total-discount"><?= number_format($total_discount,2) ?></span>
                    <span class="total-currency">บาท</span>
                </div>
                <div class="total-separator mx-3">|</div>
                <div>
                    <span class="total-label">ราคารวม:</span>
                    <span class="total-amount" id="total-amount"><?= number_format($total,2) ?></span>
                    <span class="total-currency">บาท</span>
                </div>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-primary btn-lg checkout-btn" style="font-size: 24px; padding: 10px 200px;" onclick="showCheckout()">
                    <i class="fas fa-cash-register"></i> คิดเงิน
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Overlay -->
<div id="checkout-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
    <div class="container-fluid h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0 float-left">ชำระเงิน</h5>
                        <button type="button" class="close text-white" onclick="hideCheckout()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                    <div class="form-group">
                            <label><b>ยอดชำระ</b></label>
                            <input type="text" id="total_amount" class="form-control form-control-lg" 
                                value="฿<?= number_format($total,2) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label><b>รับเงิน</b></label>
                            <input type="number" id="pay_amount2" 
                                class="form-control form-control-lg" 
                                required min="0" 
                                step="0.01"
                                onkeyup="calculateChange();">
                        </div>

                        <div class="form-group">
                            <label><b>เงินทอน</b></label>
                            <input type="text" id="change_amount" 
                                class="form-control form-control-lg" readonly>
                        </div>

                        

                        <div class="row mt-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary btn-lg btn-block" 
                                        onclick="hideCheckout()">
                                    ยกเลิก
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary btn-lg btn-block" 
                                        onclick="processPayment()">
                                    ยืนยัน
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
let isProcessing = false;
let inputTimeout = null;

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

function reloadCartTable() {
    $.ajax({
        url: 'cart_a_2.php',
        type: 'GET',
        success: function(response) {
            $('.table-responsive').html(response);
            // รีเซ็ตอีเวนต์ต่างๆ หลังจากโหลดตารางใหม่
            setupEventHandlers();
            // ผูก event listener สำหรับปุ่ม Enter ในหน้าคิดเงินใหม่
            bindCheckoutEnterKey();
        }
    });
}

function bindCheckoutEnterKey() {
    const payAmountInput = document.getElementById('pay_amount2');
    if(payAmountInput) {
        // ลบ event listener เดิมก่อน (ถ้ามี)
        payAmountInput.removeEventListener('keydown', handlePaymentEnter);
        // เพิ่ม event listener ใหม่
        payAmountInput.addEventListener('keydown', handlePaymentEnter);
    }
}

function handlePaymentEnter(e) {
    if(e.key === 'Enter') {
        e.preventDefault();
        
        // ถ้าช่องว่างเปล่า ให้ใส่จำนวนเงินเท่ากับยอดที่ต้องชำระ
        if(this.value.trim() === '') {
            const totalAmount = document.getElementById('total_amount').value.replace(/[^0-9.]/g, '');
            this.value = totalAmount;
            calculateChange(); // คำนวณเงินทอน
        } else {
            // ถ้ามีค่าอยู่แล้ว ให้ทำการชำระเงิน
            processPayment();
        }
    }
}

function addToCartByBarcode(productId) {
    if(isProcessing) return;
    isProcessing = true;

    $.ajax({
        url: 'check_product.php',
        type: 'POST',
        data: { p_id: productId },
        success: function(response) {
            if(response.trim() === 'found') {
                // ถ้าเจอสินค้า ให้เพิ่มเข้าตะกร้า
                $.ajax({
                    url: 'update_cart_qty.php',
                    type: 'POST',
                    data: {
                        p_id: productId,
                        qty: 1,
                        add_new: true
                    },
                    success: function(updateResponse) {
                        // รีโหลดเฉพาะส่วนของตาราง
                        reloadCartTable();
                        // ผูก event listener ใหม่หลังจากเพิ่มสินค้า
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

function setupEventHandlers() {
    // Modified event listeners with debounce for quantity and discount inputs
    $('.discount-input').on('input', function() {
        // Clear any existing timeout
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
        
        const input = $(this);
        updatePrice(input.closest('tr').find('.update-qty'));
        
        // Set new timeout to focus back after user stops typing
        inputTimeout = setTimeout(() => {
            if (!input.is(':focus')) return; // Don't steal focus if user moved to another field
            $('#barcode-input').focus();
        }, 1500); // รอ 1.5 วินาทีหลังจากผู้ใช้หยุดพิมพ์
    });
    
    $('.update-qty').on('input', function() {
        // Clear any existing timeout
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
        
        const input = $(this);
        updatePrice(this);
        
        // Set new timeout to focus back after user stops typing
        inputTimeout = setTimeout(() => {
            if (!input.is(':focus')) return; // Don't steal focus if user moved to another field
            $('#barcode-input').focus();
        }, 1500); // รอ 1.5 วินาทีหลังจากผู้ใช้หยุดพิมพ์
    });

    // Add blur event handlers
    $('.discount-input, .update-qty').on('blur', function() {
        // Clear timeout if user manually moves focus away
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
    });
}

function addToCart(productId) {
    window.location.href = `list_l.php?p_id=${productId}&act=add`;
}

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


function showCheckout() {
    // อัพเดทยอดเงินในหน้าคิดเงินให้ตรงกับยอดรวมปัจจุบัน
    const currentTotal = $('#total-amount').text().trim();
    $('#total_amount').val('฿' + currentTotal);
    
    // แสดงหน้าคิดเงินและโฟกัสที่ช่องรับเงิน
    $('#checkout-overlay').fadeIn(function() {
        $('#pay_amount2').val('').focus();
        $('#change_amount').val(''); // รีเซ็ตช่องเงินทอน
    });
}

function hideCheckout() {
    $('#checkout-overlay').fadeOut();
    $('#barcode-input').focus();
}

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

function clearCartTable() {
    // เคลียร์ตารางสินค้า
    $('.table tbody').empty();
    
    // อัพเดทยอดรวม
    $('#total-amount').text('0.00');
    
    // ซ่อนหน้าคิดเงิน
    hideCheckout();
    
    // รีเซ็ตค่าในหน้าคิดเงิน
    $('#pay_amount2').val('');
    $('#change_amount').val('');
    
    // โฟกัสที่ช่องสแกนบาร์โค้ด
    $('#barcode-input').focus();
}

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
            mem_id: <?php echo $_SESSION['mem_id'] ?>
        },
        success: function(response) {
            // แสดงข้อความสำเร็จ
            // alert('บันทึกการขายเรียบร้อยแล้ว');
            
            // เคลียร์ตารางและรีเซ็ตหน้าจอ
            clearCartTable();
            
            // รีเซ็ตตัวแปร Session cart (เพิ่มการเรียก PHP script)
            $.ajax({
                url: 'clear_cart_session.php',
                type: 'POST',
                success: function() {
                    console.log('Cart session cleared');
                }
            });
        }
    });
}


// Event Listeners
$(document).ready(function() {
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

    setupEventHandlers();
});

$(document).keydown(function(e) {
    if (e.key === "Escape") {
        hideCheckout();
    }
});

// โฟกัสที่ช่องบาร์โค้ดเมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('barcode-input').focus();
});

// ป้องกันการ submit form
$(document).ready(function() {
    $('.btn-success').on('click', function(e) {
        e.preventDefault();
        return false;
    });
});
</script>




<style>
/* Reset CSS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    background-color: #f4f6f9;
}

/* Layout หลัก */
.content-wrapper {
    min-height: calc(100vh - 120px); /* ลบความสูงของ fixed bottom bar */
    padding: 20px;
    background-color: #f4f6f9;
}

section.content {
    height: 100%;
}

/* ส่วนหัวและการ์ดหลัก */
.card.card-gray {
    height: 100%;
    min-height: calc(100vh - 160px); /* ลบความสูงของ padding และ bottom bar */
    margin: 0 auto;
    border-radius: 5px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    background-color: white;
}

.card-header {
    background: #6c757d;
    padding: 1rem;
    color: white;
    border-radius: 5px 5px 0 0;
}

.card-header h3 {
    font-size: 24px;
    margin: 0;
}

.card-body {
    padding: 0;
    height: calc(100% - 60px); /* ลบความสูงของ header */
}

/* ช่องค้นหา */
.input-group {
    padding: 15px;
    background: white;
}

.form-control-lg {
    height: 60px;
    font-size: 24px;
}

/* ตารางสินค้า */
.table-responsive {
    margin-bottom: 120px; /* ให้เนื้อหาไม่ทับกับ bottom bar */
}

.table {
    width: 100%;
    background: white;
}

.table thead tr {
    background: #0d6efd;
    color: white;
}

.table th {
    font-size: 24px;
    padding: 15px;
    text-align: center;
    vertical-align: middle;
}

.table td {
    font-size: 22px;
    padding: 15px;
    vertical-align: middle;
}

/* Input และ Button ในตาราง */
.table input.form-control {
    font-size: 22px;
    padding: 10px;
    height: auto;
    min-width: 100px;
}

.table .btn {
    font-size: 22px;
    padding: 10px 15px;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

/* แถบด้านล่าง */
.fixed-bottom-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: white;
    padding: 15px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.fixed-bottom-bar .row {
    margin: 0;
    max-width: 1200px;
    margin: 0 auto;
}

/* ส่วนแสดงราคาและส่วนลด */
.total-label {
    font-size: 28px;
    font-weight: bold;
    margin-right: 15px;
}

.total-amount {
    font-size: 32px;
    font-weight: bold;
    color: #28a745;
    margin-right: 10px;
}

.total-discount {
    font-size: 32px;
    font-weight: bold;
    color: #dc3545;
    margin-right: 10px;
}

.total-currency {
    font-size: 28px;
    font-weight: bold;
}

.total-separator {
    font-size: 32px;
    font-weight: bold;
    color: #6c757d;
    margin: 0 20px;
}

.checkout-btn {
    height: 60px;
    font-size: 24px;
    width: 100%;
}

/* หน้าต่างคิดเงิน */
#checkout-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: none;
}

#checkout-overlay .card {
    max-width: 500px;
    margin: 50px auto;
    border-radius: 5px;
    background: white;
}

#checkout-overlay .card-header {
    background: #007bff;
    padding: 15px;
    border-radius: 5px 5px 0 0;
}

#checkout-overlay .card-body {
    padding: 20px;
}

#checkout-overlay label {
    font-size: 24px;
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
}

#checkout-overlay .form-control {
    font-size: 28px;
    padding: 15px;
    margin-bottom: 20px;
}

#checkout-overlay .btn {
    font-size: 24px;
    padding: 15px 30px;
}

/* ขนาดคอลัมน์ในตาราง */
.table th:nth-child(1), .table td:nth-child(1) { width: 5%; }
.table th:nth-child(2), .table td:nth-child(2) { width: 10%; }
.table th:nth-child(3), .table td:nth-child(3) { width: 35%; }
.table th:nth-child(4), .table td:nth-child(4) { width: 10%; }
.table th:nth-child(5), .table td:nth-child(5) { width: 10%; }
.table th:nth-child(6), .table td:nth-child(6) { width: 5%; }
.table th:nth-child(7), .table td:nth-child(7) { width: 12%; }
.table th:nth-child(8), .table td:nth-child(8) { width: 8%; }
.table th:nth-child(9), .table td:nth-child(9) { width: 5%; }

/* Responsive */
@media (max-width: 1366px) {
    .table th { font-size: 20px; }
    .table td { font-size: 18px; }
    .table input.form-control { 
        font-size: 18px;
        padding: 8px;
    }
    .total-label { font-size: 24px; }
    .total-amount, .total-discount { font-size: 28px; }
    .total-currency { font-size: 24px; }
    .checkout-btn { font-size: 20px; }
}
</style>