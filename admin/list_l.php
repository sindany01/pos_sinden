<?php 
$menu = "sale";
include("header.php");

// ตรวจสอบและดำเนินการกับตะกร้าสินค้า
if(isset($_GET['p_id']) && isset($_GET['act'])) {
    $p_id = mysqli_real_escape_string($condb, $_GET['p_id']);
    $act = mysqli_real_escape_string($condb, $_GET['act']);
    
    if($act === 'add' && !empty($p_id)) {
        $check_product = mysqli_query($condb, "SELECT * FROM tbl_product WHERE p_id = '$p_id'");
        if(mysqli_num_rows($check_product) > 0) {
            if(!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            if(isset($_SESSION['cart'][$p_id])) {
                $_SESSION['cart'][$p_id]++;
            } else {
                $_SESSION['cart'][$p_id] = 1;
            }
        } else {
            echo "<script>
                alert('ไม่พบสินค้าหรือบาร์โค้ดไม่ถูกต้อง');
                window.location.href = 'list_l.php';
            </script>";
            exit();
        }
        header("Location: list_l.php");
        exit();
    }
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card card-gray w-100">
            <div class="card-header">
                <h3 class="card-title">ระบบขายสินค้า</h3>
            </div>
            
            <div class="card-body">
                <!-- ช่องสแกนบาร์โค้ดและหัวตาราง -->
                <div class="fixed-search-bar">
                    <form id="barcode-form" onsubmit="return handleBarcodeSubmit(event)" class="px-3 pt-3">
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                id="barcode-input"
                                class="form-control py-3"
                                placeholder="สแกนบาร์โค้ดสินค้า"
                                autocomplete="off"
                                style="font-size: 24px !important;"
                                autofocus>
                                <div class="input-group-append" style="margin-left: 20px;">
                                    <button class="btn btn-primary search-button" type="submit">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-search me-2"></i>
                                            <span class="search-text">ค้นหาสินค้า</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <!-- หัวตาราง -->
                    <div class="table-header mt-1">
                        <div class="table-wrapper">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th style="width: 5%;" class="text-center align-middle">ลำดับ</th>
                                        <th style="width: 10%;" class="text-center align-middle">บาร์โค้ด</th>
                                        <th style="width: 34%;" class="text-center align-middle">สินค้า</th>
                                        <th style="width: 10%;" class="text-center align-middle">จำนวน</th>
                                        <th style="width: 10%;" class="text-center align-middle">ราคา</th>
                                        <th style="width: 7%;" class="text-center align-middle">ส่วนลด</th>
                                        <th style="width: 12%;" class="text-center align-middle">รวม</th>
                                        <th style="width: 8%;" class="text-center align-middle">เพิ่ม/ลด</th>
                                        <th style="width: 6%;" class="text-center align-middle">ลบ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive" style="margin-top: 80px;">
                    <?php include('cart_a_2.php'); ?>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
let isProcessing = false;

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
                        add_new: true
                    },
                    success: function(updateResponse) {
                        try {
                            const result = JSON.parse(updateResponse);
                            if(result.success) {
                                reloadCartTable();
                            } else {
                                alert(result.message || 'เกิดข้อผิดพลาดในการเพิ่มสินค้า');
                            }
                        } catch(e) {
                            reloadCartTable(); // ถ้าไม่สามารถแปลง JSON ได้ ให้โหลดตารางใหม่
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการเพิ่มสินค้า');
                    },
                    complete: function() {
                        isProcessing = false;
                        $('#barcode-input').val('').focus();
                    }
                });
            } else {
                alert('ไม่พบสินค้าหรือบาร์โค้ดไม่ถูกต้อง');
                isProcessing = false;
                $('#barcode-input').val('').focus();
            }
        },
        error: function() {
            alert('เกิดข้อผิดพลาดในการตรวจสอบสินค้า');
            isProcessing = false;
            $('#barcode-input').val('').focus();
        }
    });
}

function reloadCartTable() {
    $.ajax({
        url: 'cart_a_2.php',
        type: 'GET',
        success: function(response) {
            $('.table-responsive').html(response);
            // รีเซ็ตอีเวนต์ต่างๆ หลังจากโหลดตารางใหม่
            setupEventHandlers();
        }
    });
}

function setupEventHandlers() {
    // เพิ่ม event listeners สำหรับปุ่มและ input ต่างๆ
    $('.discount-input').on('input', function() {
        updatePrice($(this).closest('tr').find('.update-qty'));
    });
    
    $('.update-qty').on('input', function() {
        updatePrice(this);
    });
}


function addToCart(productId) {
    window.location.href = `list_l.php?p_id=${productId}&act=add`;
}

$(document).ready(function() {
    $('#barcode-input').on('keydown', function(e) {
        if (e.keyCode === 13) { // ตรวจจับปุ่ม Enter
            e.preventDefault();
            let barcodeValue = $(this).val().trim();

            if (barcodeValue === '') {
                if($('.table tbody tr').length > 0) {
                    showCheckout();
                }
            } else {
                addToCartByBarcode(barcodeValue);
            }
            $(this).val('').focus();
        }
    });

    // Setup initial event handlers
    setupEventHandlers();
});

// โฟกัสที่ช่องบาร์โค้ดเมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('barcode-input').focus();
});
</script>

<style>
/* ปรับปรุง fixed-search-bar */
.fixed-search-bar {
    position: fixed;
    top: 40px;
    left: 0;
    right: 0;
    background: white;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 10px 0;
}

.table-header {
    padding: 0 15px;
    max-width: 100%;
    margin: 0 auto;
}

.table-header .table {
    table-layout: fixed;
    margin: 0 auto;
}

/* ทำให้ความกว้างของคอลัมน์ในหัวตารางตรงกับตารางด้านล่าง */
.table-header .table th,
.table-responsive .table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-header .table {
    margin-bottom: 0;
}

/* ปรับขนาดช่อง input */
.input-group-lg > .form-control {
    min-height: 60px;
}

/* ปรับขนาดปุ่ม */
.search-button {
    min-height: 60px !important;
    min-width: 200px !important;
    padding: 8px 20px !important;
    line-height: 1.2 !important;
}

/* ขนาดไอคอน */
.search-button i {
    font-size: 24px !important;
}

/* ขนาดตัวหนังสือในปุ่ม */
.search-text {
    font-size: 25px !important;
    font-weight: bold !important;
}

/* ปรับ placeholder */
.input-group-lg > .form-control::placeholder {
    font-size: 24px !important;
}

/* ปรับความกว้างของฟอร์มค้นหา */
#barcode-form {
    max-width: 50%;
    margin: 0 auto;
}

/* เพิ่มระยะห่างสำหรับเนื้อหาตาราง */
.table-responsive {
    padding-top: 0px;
}

/* ให้ตารางมีความกว้างเท่ากับหัวตาราง */
.table {
    width: 99%;
    margin: 0;
}

/* ซ่อนหัวตารางเดิมในส่วนของ cart_a_2.php */
.table-responsive .table thead {
    display: none;
}
</style>

<?php include('footer.php'); ?>