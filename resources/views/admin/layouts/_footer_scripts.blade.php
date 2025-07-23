  <!-- BEGIN VENDOR JS-->
  <script src="{{ asset('assets/admin/') }}/vendors/js/vendors.min.js" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('assets/admin/') }}/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
  <script src="{{ asset('assets/admin/') }}/vendors/js/charts/chartist-plugin-tooltip.min.js" type="text/javascript">
  </script>
  <script src="{{ asset('assets/admin/') }}/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
  <script src="{{ asset('assets/admin/') }}/vendors/js/charts/morris.min.js" type="text/javascript"></script>
  <script src="{{ asset('assets/admin/') }}/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN MODERN JS-->
  <script src="{{ asset('assets/admin/') }}/js/core/app-menu.js" type="text/javascript"></script>
  <script src="{{ asset('assets/admin/') }}/js/core/app.js" type="text/javascript"></script>
  <script src="{{ asset('assets/admin/') }}/js/scripts/customizer.js" type="text/javascript"></script>
  <!-- END MODERN JS-->
  <!-- BEGIN PAGE LEVEL JS-->
  <script src="{{ asset('assets/admin/') }}/js/scripts/pages/dashboard-ecommerce.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL JS-->
  <script src="{{ asset('assets/admin/') }}/js/scripts/pages/email-application.js" type="text/javascript"></script>
  <!---- Sweat Alert  --->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-------- File Input --------->
  <script src="{{ asset('vendor/fileinput/js/fileinput.min.js') }}"></script>
  <script src="{{ asset('vendor/fileinput/themes/bs5/theme.min.js') }}"></script>
  @if (Config::get('app.locale') == 'ar')
      <script src="{{ asset('vendor/fileinput/js/locales/LANG.js') }}"></script>
      <script src="{{ asset('vendor/fileinput/js/locales/ar.js') }}"></script>
  @endif
  <!-------- End File Input ---------->
  @yield('js')
  @toastifyJs
  <script>
      /////// Translation --
      let title = "هل انت متاكد من حذف هذا العنصر؟";
      //////// End Translation
      $(document).on('click', '.delete_confirm', function(e) {
          e.preventDefault();
          form = $(this).closest('form');
          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                  confirmButton: "btn btn-success",
                  cancelButton: "btn btn-danger"
              },
              buttonsStyling: true
          });
          swalWithBootstrapButtons.fire({
              title: title,
           //   text: "هل انت متاكد من حذف هذا العنصر؟",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "نعم حذف",
              cancelButtonText: "لا",
              reverseButtons: true
          }).then((result) => {
              if (result.isConfirmed) {
                  form.submit();
                  swalWithBootstrapButtons.fire({
                      title: "تم الحذف!",
                      text: "تم حذف العنصر.",
                      icon: "success"
                  });
              } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
              ) {
                  swalWithBootstrapButtons.fire({
                      title: "تم الالغاء",
                      text: "تم الالغاء حذف العنصر.",
                      icon: "error"
                  });
              }
          });

      });
  </script>


  <!-- Start file Input  -->
  <script>
    var lang = "{{ app()->getLocale() }}";
      $("#single-image").fileinput({
          theme: 'fa5',
          allowedFileTypes: ['image'],
          language:lang,
          maxFileCount: 1,
          enableResumableUpload: false,
          showUpload: false,
      });
  </script>
  <!-- End File Input -->
