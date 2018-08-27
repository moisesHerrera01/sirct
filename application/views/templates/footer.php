<?php $ruta_segmento = trim(obtener_segmentos(2)); ?>
</div>
   
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/viaticos_validation.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sidebarmenu.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script> jQuery(document).ready(function() { $(".select2").select2(); $('.selectpicker').selectpicker(); }); </script>
    <script src="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/moment-with-locales.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/plugins/toast-master/js/jquery.toast.js"></script>

<?php if($ruta_segmento == "configuraciones/informacion_empleado"){ ?>
    <script src="<?php echo base_url(); ?>assets/plugins/cropper/cropper.js"></script>
<?php } ?>

<?php if($ruta_segmento == "pagos/emergencias" || $ruta_segmento == "pasajes/pasaje"){ ?>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<?php } ?>

<?php if($ruta_segmento == "viaticos/solicitud_viatico"){ ?>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/dropify/dist/js/dropify.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/mask.js"></script>
<?php } ?>
 
<?php if($ruta_segmento == "informes/menu_reportes"){ ?>
    <!-- JS para reportes  -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider-init.js"></script>
<?php } ?>

</body>

</html>