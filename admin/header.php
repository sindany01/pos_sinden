<?php include ("head.php"); ?>

<body class="layout-fixed layout-navbar-fixed text-sm">
<!-- Site wrapper -->
<div class="wrapper">
  
  <?php include ("navbar.php"); ?>

<style>
/* ปรับแต่งให้เนื้อหาแสดงเต็มจอ */
.wrapper {
    min-height: 100%;
}

.content-wrapper {
    margin-left: 0 !important;
    min-height: calc(100vh - 57px); /* ความสูงทั้งหมดลบด้วยความสูง navbar */
}

.main-header {
    margin-left: 0 !important;
    width: 100% !important;
}

/* ถ้ามี footer ให้ปรับความสูงของ content-wrapper */
.main-footer {
    margin-left: 0 !important;
    width: 100% !important;
}


</style>

<userStyle>Normal</userStyle>