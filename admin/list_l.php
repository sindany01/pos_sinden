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

echo '<link rel="stylesheet" href="sale.css?v='.time().'">';
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
echo '<script src="sale.js"></script>';
?>

<div class="content-wrapper">
    <div class="sales-container">
        <!-- Header Section with Totals -->
        <div class="sales-header">
            <div class="datetime-display">
                <i class="fas fa-calendar-alt"></i>
                <span id="current-date"></span>
            </div>
            <div class="totals-display">
                <?php
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
                ?>
                <span class="total-label">ส่วนลดรวม:</span>
                <span class="total-value discount-value" id="total-discount"><?= number_format($total_discount,2) ?></span>
                <span class="currency">บาท</span>
                <span class="separator">|</span>
                <span class="total-label">ราคารวม:</span>
                <span class="total-value grand-value" id="total-amount"><?= number_format($total,2) ?></span>
                <span class="currency">บาท</span>
            </div>
        </div>

        <!-- Barcode Scanner Section -->
        <div class="scanner-section">
            <form id="barcode-form" onsubmit="return handleBarcodeSubmit(event)">
                <div class="input-group">
                    <div class="barcode-icon">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <input type="text" 
                        id="barcode-input"
                        class="form-control"
                        placeholder="สแกนบาร์โค้ดสินค้า หรือ กด Enter เพื่อคิดเงิน"
                        autocomplete="off"
                        autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-primary search-button" type="submit">
                            <i class="fas fa-search"></i>
                            <span>ค้นหาสินค้า</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cart Table -->
        <div class="cart-section">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>บาร์โค้ด</th>
                            <th>สินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคา/หน่วย</th>
                            <th>ส่วนลด</th>
                            <th>รวม</th>
                            <th>ปรับจำนวน</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!empty($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $p_id=>$qty) {
                                $sql = "SELECT * FROM tbl_product WHERE p_id=$p_id";
                                $query = mysqli_query($condb, $sql);
                                if ($query && $row = mysqli_fetch_array($query)) {
                                    $sum = $row['p_price'] * $qty;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= @$i+=1 ?></td>
                                        <td class="text-center"><?= $p_id ?></td>
                                        <td>
                                            <div class="product-info">
                                                <span class="product-name"><?= $row["p_name"] ?></span>
                                                <span class="stock-info">สต๊อก <?= $row['p_qty'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                name="amount[<?= $p_id ?>]" 
                                                value="<?= $qty ?>" 
                                                class="form-control update-qty" 
                                                min="1" 
                                                max="<?= $row['p_qty'] ?>"
                                                data-id="<?= $p_id ?>"
                                                data-price="<?= $row["p_price"] ?>"
                                                oninput="updatePrice(this)"/>
                                        </td>
                                        <td class="text-right price-<?= $p_id ?>">
                                            <?= number_format($row["p_price"],2) ?>
                                        </td>
                                        <td>
                                            <input type="number"
                                                name="discount[<?= $p_id ?>]"
                                                value="<?= isset($_SESSION['discount'][$p_id]) ? $_SESSION['discount'][$p_id] : 0 ?>"
                                                class="form-control discount-input"
                                                min="0"
                                                max="<?= $sum ?>"
                                                data-id="<?= $p_id ?>"
                                                oninput="updatePrice($(this).closest('tr').find('.update-qty'))"/>
                                        </td>
                                        <td class="text-right sum-<?= $p_id ?>">
                                            <?= number_format($sum - (isset($_SESSION['discount'][$p_id]) ? $_SESSION['discount'][$p_id] : 0),2) ?>
                                        </td>
                                        <td>
                                            <div class="quantity-controls">
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
                                        <td>
                                            <a href="list_l.php?p_id=<?= $p_id ?>&act=remove" 
                                               class="btn btn-danger delete-btn">
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

        <!-- Checkout Button Section -->
        <div class="checkout-section">
            <button type="button" class="btn btn-primary checkout-btn" onclick="showCheckout()">
                <i class="fas fa-cash-register"></i>
                <span>คิดเงิน</span>
            </button>
        </div>
    </div>

    <!-- Checkout Overlay -->
    <div id="checkout-overlay">
        <div class="checkout-dialog">
            <div class="checkout-content">
                <div class="checkout-header">
                    <h3>ชำระเงิน</h3>
                    <button type="button" class="close-btn" onclick="hideCheckout()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="checkout-body">
                    <div class="form-group">
                        <label>ยอดชำระ</label>
                        <input type="text" id="total_amount" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>รับเงิน</label>
                        <input type="number" 
                               id="pay_amount2" 
                               class="form-control" 
                               required 
                               min="0" 
                               step="0.01"
                               onkeyup="calculateChange();">
                    </div>
                    <div class="form-group">
                        <label>เงินทอน</label>
                        <input type="text" id="change_amount" class="form-control" readonly>
                    </div>
                </div>
                <div class="checkout-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCheckout()">
                        ยกเลิก
                    </button>
                    <button type="button" class="btn btn-primary" onclick="processPayment()">
                        ยืนยัน
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>