<!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>© 2018 CacicusTech</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Seguro desea salir?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">CANCELAR</button>
            <a class="btn btn-primary" href="salir">CERRAR SESIÓN</a> 
          </div>
        </div>
      </div>
    </div>
    <script src="src/admin/vendor/bootstrap-notify/bootstrap-notify.js"></script>
    
    <script src="src/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="src/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="src/admin/vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="src/admin/vendor/bootstrap-tags/dist/bootstrap-tagsinput.js"></script>
    <script src="src/admin/vendor/datatables/jquery.dataTables.js"></script>
    <script src="src/admin/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="src/admin/js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script type="text/javascript">
      $(function () {
          if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
            $('.selectpicker').selectpicker('mobile');
          }
          $('.selectpicker').selectpicker({
              dropupAuto: false
          });
          //Tooltip
          $('[data-toggle="tooltip"]').tooltip({
              container: 'body'
          });

          //Popover
          $('[data-toggle="popover"]').popover();
            //CONFIGURACIÓN GENERAL PARA LAS NOTIFICACIONES
            $.notifyDefaults({
                type: 'bg-black',
                allow_dismiss: true,
                newest_on_top: true,
                timer: 1000,
                placement: {
                    from: "bottom",
                    align: "right"
                },
                animate: {
                    enter: "animated fadeInUp",
                    exit: "animated fadeOutUp"
                },
                template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + ("p-r-35") + '" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title" style="text-align:left;">{1}</span> ' +
                '<span data-notify="message" style="text-align:left;">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
            });
        });
    </script>
  </body>

</html>
