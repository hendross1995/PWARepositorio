<footer class="page-footer text-center font-small accent-4 darken-2 mt-4 wow fadeIn">
    <hr class="my-4">
    <div class="footer-copyright py-3">
        © 2018 Copyright
        <a href="http://cacicustech.com/" target="_blank"> www.cacicustech.com </a>
    </div>
</footer>

<script src="src/admin/vendor/bootstrap-notify/bootstrap-notify.js"></script>
<script src="src/admin/vendor/bootstrap-tags/dist/bootstrap-tagsinput.js"></script>

<script src="src/lector/js/jquery.simplePagination.js"></script>
<script src="src/lector/js/bootstrap-select.min.js"></script>
<script src="src/lector/js/jquery.scrollUp.js"></script>

<script src="src/lector/plugins/flipbook/js/html2canvas.min.js"></script>
<script src="src/lector/plugins/flipbook/js/three.min.js"></script>
<script src="src/lector/plugins/flipbook/js/pdf.min.js"></script>
<script type="text/javascript">
  window.PDFJS_LOCALE = {
    pdfJsWorker: 'src/lector/plugins/flipbook/js/pdf.worker.js'
  };
</script>
<script src="src/lector/plugins/flipbook/js/3dflipbook.min.js"></script>
<script src="src/lector/plugins/flipbook/js/lightbox.js"></script>

<script src="src/admin/vendor/datatables/jquery.dataTables.js"></script>
<script src="src/admin/vendor/datatables/dataTables.bootstrap4.js"></script>

<script type="text/javascript">
    $(function(){
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
         $('.selectpicker').selectpicker('mobile');
      }
      $('.selectpicker').selectpicker({
          dropupAuto: false
      });
      
     $.scrollUp({
         scrollText:"",
         scrollSpeed:2050,
         easingType: "easeOutQuint"
     })
    $.notifyDefaults({
      type: 'bg-black',
      allow_dismiss: true,
      newest_on_top: true,
      timer: 1000,
      placement: {
          from: "bottom",
          align: "left"
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
