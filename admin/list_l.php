sale<?php 
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

// เพิ่ม CSS และ JavaScript
echo '<link rel="stylesheet" href="sale.css">';
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
echo '<script src="sale.js"></script>';
?>

<div class="content-wrapper">
    <!-- ช่องสแกนบาร์โค้ดและหัวตาราง -->
    <div class="d-flex">
        <form id="barcode-form" onsubmit="return handleBarcodeSubmit(event)" class="justify-content-start">
            <div class="input-group bg-success">
                <input type="text" 
                    id="barcode-input"
                    class="form-control py-4"
                    placeholder="สแกนบาร์โค้ดสินค้า"
                    autocomplete="off"
                    autofocus>
                <div class="input-group-append">
                    <button class="btn btn-primary search-button" type="submit">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-search me-2"></i>
                            <span class="search-text">ค้นหาสินค้า</span>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ตารางสินค้า -->
    <div class="card-body">
        <div class="card card-gray">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
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
                                            </td>
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
                        }
                        ?>
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

    <!-- แถบด้านล่าง -->
    <div class="fixed-bottom-bar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="real-time-date text-left">
                    <span id="current-date" style="font-size: 20px; font-weight: bold;"></span>
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

    <!-- หน้าต่างคิดเงิน -->
    <div id="checkout-overlay">
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
</div>

<?php include('footer.php'); ?>