</div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 2.2.0
    </div>
    <!-- <strong>Copyright &copy; 2021 POS System<a href="http://fordev22.com/">fordev22</a>.</strong> All rights -->
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- Bootstrap 4 -->
<!-- http://fordev22.com/ -->
<script src="../assets/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../assets/jquery.dataTables.js"></script>
<script src="../assets/dataTables.bootstrap4.js"></script>
<script src="../assets/tagsinput.js?v=1"></script>

<!-- Select2 -->
<!-- http://fordev22.com/ -->
<script src="../assets/sweetalert2@9.js"></script>



<!-- AdminLTE App -->
<script src="../assets/adminlte.min.js"></script>





<!-- AdminLTE for demo purposes -->
<!-- <script src="assets/dist/js/demo.js"></script> -->
<!-- http://fordev22.com/ -->

<script>
  $(document).ready(function () {
    //$('.sidebar-menu').tree();
    //$('.select2').select2();
    //Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    })
  })
</script>




<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<!-- http://fordev22.com/ -->