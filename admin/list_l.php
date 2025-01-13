<?php 
session_start();
$menu = "sale";
include("header.php");

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
        }
        header("Location: list_l.php");
        exit();
    }
}
?>

<!-- เพิ่ม Font Prompt -->
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- เพิ่ม CSS ที่ปรับปรุงใหม่ -->
<link rel="stylesheet" href="sale.css">
<!-- เพิ่ม CSS สำหรับ Navbar -->
<style>
    .main-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }
    
    /* ปรับ sidebar ให้อยู่ใต้ navbar */
    .main-sidebar {
        padding-top: 60px;
    }
    
    /* ปรับ content ให้ไม่ทับกับ navbar */
    .content-wrapper {
        padding-top: calc(1rem + 60px);
    }
    
    @media (max-width: 768px) {
        .content-wrapper {
            margin-top: 60px;
            padding-top: 1rem;
        }
    }
</style>

<div class="content-wrapper">
    <!-- ส่วนค้นหา -->
    <div class="search-section">
        <form id="barcode-form" onsubmit="return handleBarcodeSubmit(event)" class="d-flex justify-content-center">
            <div class="input-group" style="max-width: 800px;">
                <input type="text" 
                    id="barcode-input"
                    class="form-control search-input"
                    placeholder="สแกนบาร์โค้ดสินค้า หรือ ค้นหาสินค้า"
                    autocomplete="off"
                    autofocus>
                <div class="input-group-append">
                    <button class="btn search-button" type="submit">
                        <i class="fas fa-search me-2"></i>
                        <span>ค้นหา</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ตารางสินค้า -->
    <div class="product-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">บาร์โค้ด</th>
                        <th>สินค้า</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-center">ราคา</th>
                        <th class="text-center">ส่วนลด</th>
                        <th class="text-center">รวม</th>
                        <th class="text-center">จัดการ</th>
                        <th class="text-center">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if(!empty($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $p_id=>$qty) {
                            $sql = "SELECT * FROM tbl_product WHERE p_id=$p_id";
                            $query = mysqli_query($condb, $sql);
                            if ($query && $row = mysqli_fetch_array($query)) {
                                $sum = $row['p_price'] * $qty;
                                $total += $sum;
                                ?>
                                <tr>
                                    <td class="text-center"><?= @$i+=1 ?></td>
                                    <td class="text-center"><?= $p_id ?></td>
                                    <td>
                                        <?= $row["p_name"] ?>
                                        <div class="text-muted">คงเหลือ: <?= $row['p_qty'] ?></div>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" 
                                            class="form-control quantity-input update-qty" 
                                            value="<?= $qty ?>" 
                                            min="1" 
                                            max="<?= $row['p_qty'] ?>"
                                            data-id="<?= $p_id ?>"
                                            data-price="<?= $row["p_price"] ?>">
                                    </td>
                                    <td class="text-center price-<?= $p_id ?>">
                                        <?= number_format($row["p_price"],2) ?>
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                            class="form-control discount-input"
                                            value="<?= isset($_SESSION['discount'][$p_id]) ? $_SESSION['discount'][$p_id] : 0 ?>"
                                            min="0"
                                            max="<?= $sum ?>"
                                            data-id="<?= $p_id ?>">
                                    </td>
                                    <td class="text-center sum-<?= $p_id ?>">
                                        <?= number_format($sum,2) ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" 
                                                class="btn btn-quantity btn-danger"
                                                onclick="decrementQuantity(this)"
                                                data-id="<?= $p_id ?>">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" 
                                                class="btn btn-quantity btn-success"
                                                onclick="incrementQuantity(this)"
                                                data-id="<?= $p_id ?>"
                                                data-max="<?= $row['p_qty'] ?>">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-remove" onclick="removeItem(<?= $p_id ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- แถบด้านล่าง -->
    <div class="bottom-bar">
        <div class="container-fluid">
            <div class="total-section">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <span class="total-text">ส่วนลดรวม:</span>
                        <span class="total-amount" id="total-discount">
                            <?= number_format($total_discount ?? 0, 2) ?>
                        </span>
                        <span class="total-text">บาท</span>
                    </div>
                    <div class="mx-4">
                        <span class="total-text">ยอดรวม:</span>
                        <span class="total-amount" id="total-amount">
                            <?= number_format($total, 2) ?>
                        </span>
                        <span class="total-text">บาท</span>
                    </div>
                </div>
                <button type="button" class="btn checkout-button" onclick="showCheckout()">
                    <i class="fas fa-cash-register me-2"></i>
                    ชำระเงิน
                </button>
            </div>
        </div>
    </div>

    <!-- หน้าต่างชำระเงิน -->
    <div id="checkout-overlay" class="checkout-overlay position-fixed top-0 start-0 w-100 h-100 d-none">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="checkout-modal">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0" style="font-size: 28px; color: var(--primary-orange);">ชำระเงิน</h3>
                            <button type="button" class="btn btn-lg" onclick="hideCheckout()" style="font-size: 24px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold">ยอดชำระ</label>
                            <input type="text" id="total_amount" class="form-control" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold">รับเงิน</label>
                            <input type="number" id="pay_amount2" class="form-control" min="0" step="0.01" onkeyup="calculateChange();">
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold">เงินทอน</label>
                            <input type="text" id="change_amount" class="form-control" readonly>
                        </div>

                        <div class="d-flex justify-content-between gap-3">
                            <button type="button" class="btn btn-secondary btn-lg w-50" onclick="hideCheckout()" style="font-size: 24px;">
                                ยกเลิก
                            </button>
                            <button type="button" class="btn btn-success btn-lg w-50" onclick="processPayment()" style="font-size: 24px;">
                                ยืนยัน
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="sale.js"></script>

<?php include('footer.php'); ?>