<?php
session_start();

// เคลียร์ข้อมูลตะกร้าสินค้าทั้งหมด
function clearCart() {
    // เคลียร์ array ตะกร้าสินค้า
    if(isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    
    // เคลียร์ข้อมูลส่วนลด
    if(isset($_SESSION['discount'])) {
        unset($_SESSION['discount']);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'ล้างตะกร้าสินค้าเรียบร้อยแล้ว'
    ]);
}

// ตรวจสอบว่ามีการเรียกใช้งานผ่าน AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    clearCart();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>