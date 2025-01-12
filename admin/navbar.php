<nav class="main-header navbar navbar-expand navbar-gray navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Brand Logo -->
        <!-- <li class="nav-item">
            <a href="" class="nav-link">
                <img src="../assets/img/ffd2222.png" alt="Logo" class="brand-image" style="height: 30px;">
                <span class="brand-text font-weight-light">FD22 | POS System</span>
            </a>
        </li> -->

        <!-- เมนูสำหรับการขาย -->
        <li class="nav-header nav-link text-white">เมนูสำหรับการขาย</li>

        <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($menu=="index"){echo "active";} ?>">
                <i class="nav-icon fas fa-clipboard-list"></i> รายการขาย
            </a>
        </li>

        <li class="nav-item">
            <a href="list_l.php" class="nav-link <?php if($menu=="sale"){echo "active";} ?>">
                <i class="nav-icon fa fa-shopping-cart"></i> ขายสินค้า
            </a>
        </li>

        <!-- ตั้งค่าข้อมูลระบบ -->
        <li class="nav-header nav-link text-white">ตั้งค่าข้อมูลระบบ</li>

        <li class="nav-item">
            <a href="list_mem.php" class="nav-link <?php if($menu=="member"){echo "active";} ?>">
                <i class="nav-icon fa fa-users"></i> Member
            </a>
        </li>

        <li class="nav-item">
            <a href="" class="nav-link <?php if($menu=="type"){echo "active";} ?>">
                <i class="nav-icon fa fa-copy"></i> Type
            </a>
        </li>

        <li class="nav-item">
            <a href="" class="nav-link <?php if($menu=="brand"){echo "active";} ?>">
                <i class="nav-icon fa fa-box"></i> Brand
            </a>
        </li>

        <li class="nav-item">
            <a href="list_product.php" class="nav-link <?php if($menu=="product"){echo "active";} ?>">
                <i class="nav-icon fa fa-box-open"></i> Product
            </a>
        </li>

        <!-- Dashboard -->
        <li class="nav-header nav-link text-white">Dashboard</li>

        <li class="nav-item">
            <a href="report_p5.php" class="nav-link <?php if($menu=="report_p5"){echo "active";} ?>">
                <i class="nav-icon fas fa-crown text-fuchsia"></i> 5 อันดับสินค้าขายดี
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- ข้อมูลผู้ใช้ -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                <!-- <img src="../mem_img/<?php echo $_SESSION['mem_img'];?>" class="img-circle elevation-2" alt="User Image" style="height: 30px; width: 30px; margin-right: 5px;"> -->
                <?php echo $_SESSION['mem_name'];?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="edit_profile.php">
                    <i class="fas fa-user-edit"></i> แก้ไขโปรไฟล์
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="../logout.php">
                    <i class="fas fa-power-off"></i> ออกจากระบบ
                </a>
            </div>
        </li>
    </ul>
</nav>

<style>
/* ปรับแต่ง Navbar */
.navbar {
    padding: 0 1rem;
    min-height: 57px;
}

.navbar-nav .nav-link {
    padding: 0.7rem 1rem;
    margin: 0 0.2rem;
    border-radius: 4px;
    transition: all 0.3s;
}

.navbar-nav .nav-header {
    padding: 0.5rem 1rem;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255,255,255,0.1);
}

.navbar-nav .nav-link.active {
    background-color: rgba(255,255,255,0.2);
}

.brand-image {
    max-height: 35px;
    margin-right: 0.5rem;
}

.dropdown-menu {
    margin-top: 8px;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

.dropdown-item {
    padding: 0.5rem 1.5rem;
}

.dropdown-item i {
    width: 1.5rem;
    text-align: center;
    margin-right: 0.5rem;
}

.img-circle {
    border-radius: 50%;
    object-fit: cover;
}
</style>