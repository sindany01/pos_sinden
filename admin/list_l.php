<?php 
$menu = "sale";
include("header.php");

// ตรวจสอบและดำเนินการกับตะกร้าสินค้า
if(isset($_GET['p_id']) && isset($_GET['act'])) {
    $p_id = $_GET['p_id'];
    $act = $_GET['act'];
    
    if($act == 'remove' && !empty($p_id)) {
        if(isset($_SESSION['cart'][$p_id])) {
            unset($_SESSION['cart'][$p_id]);
            if(isset($_SESSION['discount'][$p_id])) {
                unset($_SESSION['discount'][$p_id]);
            }
            echo "<script>window.location='list_l.php';</script>";
            exit();
        }
    }
    
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
                window.location='list_l.php';
            </script>";
            exit();
        }
        echo "<script>window.location='list_l.php';</script>";
        exit();
    }
}

// คำนวณยอดรวมและส่วนลด
$total_discount = 0;
$total = 0;
if(!empty($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $p_id=>$qty) {
        if(isset($_SESSION['discount'][$p_id])) {
            $total_discount += $_SESSION['discount'][$p_id];
        }
        $sql = "SELECT * FROM tbl_product WHERE p_id=$p_id";
        $query = mysqli_query($condb, $sql);
        if ($query && $row = mysqli_fetch_array($query)) {
            $sum = $row['p_price'] * $qty;
            if(isset($_SESSION['discount'][$p_id])) {
                $sum -= $_SESSION['discount'][$p_id];
            }
            $total += $sum;
        }
    }
}

echo '<link rel="stylesheet" href="sale.css?v='.time().'">';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- ส่วนแสดงวันที่และเวลา -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-primary mb-0">
                                <i class="fas fa-calendar-alt"></i> 
                                <span id="current-date"></span>
                            </h4>
                        </div>
                    </div>
                </div>
                
                <!-- ส่วนแสดงยอดรวม -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-right">
                            <h4>ส่วนลดรวม: ฿<span id="total-discount"><?= number_format($total_discount, 2) ?></span></h4>
                            <h3 class="text-primary mb-0"> ฿<span id="total-amount"><?= number_format($total, 2) ?></span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ส่วนสแกนบาร์โค้ด -->
            <div class="card mb-3">
                <div class="card-body">
                    <form id="barcode-form" onsubmit="return handleBarcodeSubmit(event)">
                        <div class="input-group">
                            <input type="text" 
                                id="barcode-input" 
                                class="form-control form-control-lg" 
                                placeholder="สแกนบาร์โค้ดสินค้า หรือ กด Enter เพื่อคิดเงิน"
                                autocomplete="off"
                                autofocus>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-barcode"></i> สแกน
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ส่วนตารางสินค้า -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive" style="height: calc(100vh - 450px);">
                        <table class="table table-hover mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center align-middle" width="5%">ลำดับ</th>
                                    <th class="text-center align-middle" width="15%">บาร์โค้ด</th>
                                    <th class="align-middle" width="25%">สินค้า</th>
                                    <th class="text-center align-middle" width="10%">จำนวน</th>
                                    <th class="text-right align-middle" width="10%">ราคา/หน่วย</th>
                                    <th class="text-center align-middle" width="10%">ส่วนลด</th>
                                    <th class="text-right align-middle" width="10%">รวม</th>
                                    <th class="text-center align-middle" width="10%">ปรับจำนวน</th>
                                    <th class="text-center align-middle" width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($_SESSION['cart'])) {
                                    $i = 0;
                                    foreach($_SESSION['cart'] as $p_id=>$qty) {
                                        $sql = "SELECT * FROM tbl_product WHERE p_id=$p_id";
                                        $query = mysqli_query($condb, $sql);
                                        if ($query && $row = mysqli_fetch_array($query)) {
                                            $sum = $row['p_price'] * $qty;
                                            $discount = isset($_SESSION['discount'][$p_id]) ? $_SESSION['discount'][$p_id] : 0;
                                            $final_sum = $sum - $discount;
                                            ?>
                                            <tr>
                                                <td class="text-center align-middle"><?= ++$i ?></td>
                                                <td class="text-center align-middle"><?= $p_id ?></td>
                                                <td class="align-middle">
                                                    <div class="font-weight-bold"><?= $row["p_name"] ?></div>
                                                    <small class="text-muted">สต๊อก: <?= $row['p_qty'] ?></small>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <input type="number" 
                                                        value="<?= $qty ?>" 
                                                        class="form-control update-qty text-center" 
                                                        min="1" 
                                                        max="<?= $row['p_qty'] ?>"
                                                        data-id="<?= $p_id ?>"
                                                        data-price="<?= $row["p_price"] ?>"
                                                        oninput="updatePrice(this)"/>
                                                </td>
                                                <td class="text-right align-middle"><?= number_format($row["p_price"], 2) ?></td>
                                                <td class="text-center align-middle">
                                                    <input type="number"
                                                        value="<?= $discount ?>"
                                                        class="form-control discount-input text-center"
                                                        min="0"
                                                        max="<?= $sum ?>"
                                                        data-id="<?= $p_id ?>"
                                                        oninput="updatePrice($(this).closest('tr').find('.update-qty'))"/>
                                                </td>
                                                <td class="text-right align-middle sum-<?= $p_id ?>"><?= number_format($final_sum, 2) ?></td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-group">
                                                        <button type="button" 
                                                            class="btn btn-danger"
                                                            onclick="decrementQuantity(this)"
                                                            data-id="<?= $p_id ?>">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <button type="button" 
                                                            class="btn btn-success"
                                                            onclick="incrementQuantity(this)"
                                                            data-id="<?= $p_id ?>"
                                                            data-max="<?= $row['p_qty'] ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="list_l.php?p_id=<?= $p_id ?>&act=remove" 
                                                    class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
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
            </div>

            <!-- ส่วนปุ่มคิดเงิน -->
            <div class="text-right mt-3">
                <button type="button" class="btn btn-primary btn-lg px-4 buttonsale" onclick="showCheckout()">
                    <i class="fas fa-cash-register"></i> คิดเงิน (Enter)
                </button>
            </div>
        </div>
    </section>

    <!-- Modal คิดเงิน -->
    <div id="checkout-overlay" style="display: none;" class="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title mb-0">ชำระเงิน</h4>
                    <button type="button" class="close text-white" onclick="hideCheckout()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">ยอดชำระ</label>
                        <input type="text" id="total_amount" class="form-control form-control-lg text-right" readonly>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">รับเงิน</label>
                        <input type="number" 
                            id="pay_amount2" 
                            class="form-control form-control-lg text-right" 
                            min="0" 
                            step="0.01"
                            onkeyup="calculateChange()">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">เงินทอน</label>
                        <input type="text" id="change_amount" class="form-control form-control-lg text-right" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg" onclick="hideCheckout()">
                        ยกเลิก (Esc)
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="processPayment()">
                        ยืนยัน (Enter)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1050;
    display: none;
}

.modal-dialog {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    margin: 0;
}

/* Input Styles */
.update-qty,
.discount-input {
    width: 80px;
}

.form-control-lg {
    font-size: 1.25rem;
}

/* Table Styles */
.table thead th {
    position: sticky;
    top: 0;
    z-index: 1;
    font-size: 1.1rem;
    white-space: nowrap;
    background-color:rgb(255, 149, 0);
}

.table td {
    font-size: 1.1rem;
}

/* Custom Styles */
.card-title {
    font-size: 1.5rem;
}

.bg-primary {
    background-color:rgb(255, 162, 0) !important;
}

.text-primary {
    color:rgb(241, 154, 14) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .update-qty,
    .discount-input {
        width: 60px;
    }
    
    .table td {
        font-size: 1rem;
    }
}
</style>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sale.js?v=<?= time() ?>"></script>

<?php include('footer.php'); ?>