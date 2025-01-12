<?php
session_start();
if(isset($_POST['p_id']) && isset($_POST['qty'])) {
    $p_id = $_POST['p_id'];
    $qty = (int)$_POST['qty'];
    
    if($qty > 0) {
        $_SESSION['cart'][$p_id] = $qty;
    } else {
        unset($_SESSION['cart'][$p_id]);
    }
    echo 'success';
}
?>

<?php
include('condb.php'); // เชื่อมต่อฐานข้อมูล

$p_id = mysqli_real_escape_string($condb, $_POST['p_id']); 
$qty = mysqli_real_escape_string($condb, $_POST['qty']);

if (!empty($p_id)) {
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id] += $qty; // เพิ่มจำนวนสินค้าในตะกร้า
    } else {
        $_SESSION['cart'][$p_id] = $qty; // เพิ่มสินค้าใหม่
    }

    echo json_encode(['success' => true]); // ส่งผลลัพธ์กลับ
} else {
    echo json_encode(['success' => false]); // หากไม่มี `p_id`
}
?>

<?php
include('condb.php');

if(isset($_POST['p_id'])) {
    $p_id = mysqli_real_escape_string($condb, $_POST['p_id']);
    $qty = isset($_POST['qty']) ? (int)mysqli_real_escape_string($condb, $_POST['qty']) : 0;
    $discount = isset($_POST['discount']) ? (float)mysqli_real_escape_string($condb, $_POST['discount']) : 0;

    if ($qty > 0) {
        // อัพเดทจำนวนสินค้า
        $_SESSION['cart'][$p_id] = $qty;
        
        // จัดการส่วนลด
        if (!isset($_SESSION['discount'])) {
            $_SESSION['discount'] = array();
        }
        $_SESSION['discount'][$p_id] = $discount;
        
        echo json_encode([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    } else {
        // ถ้าจำนวน = 0 ให้ลบออกจากตะกร้า
        unset($_SESSION['cart'][$p_id]);
        if (isset($_SESSION['discount'][$p_id])) {
            unset($_SESSION['discount'][$p_id]);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>

<?php
session_start();
include('condb.php');

if(isset($_POST['p_id'])) {
    $p_id = mysqli_real_escape_string($condb, $_POST['p_id']);
    $qty = isset($_POST['qty']) ? (int)mysqli_real_escape_string($condb, $_POST['qty']) : 0;
    $discount = isset($_POST['discount']) ? (float)mysqli_real_escape_string($condb, $_POST['discount']) : 0;
    $add_new = isset($_POST['add_new']) && $_POST['add_new'] == 'true';

    if ($add_new) {
        // กรณีเพิ่มสินค้าใหม่
        if (isset($_SESSION['cart'][$p_id])) {
            $_SESSION['cart'][$p_id]++;
        } else {
            $_SESSION['cart'][$p_id] = 1;
        }
    } else if ($qty > 0) {
        // กรณีอัพเดทจำนวน
        $_SESSION['cart'][$p_id] = $qty;
        
        // จัดการส่วนลด
        if (!isset($_SESSION['discount'])) {
            $_SESSION['discount'] = array();
        }
        $_SESSION['discount'][$p_id] = $discount;
    } else {
        // ถ้าจำนวน = 0 ให้ลบออกจากตะกร้า
        unset($_SESSION['cart'][$p_id]);
        if (isset($_SESSION['discount'][$p_id])) {
            unset($_SESSION['discount'][$p_id]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>