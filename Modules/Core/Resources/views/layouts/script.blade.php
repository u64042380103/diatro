<script src="/assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/assets/lib/perfect-scrollbar/js/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="/assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="/assets/js/app.js" type="text/javascript"></script>
<script src="/assets/lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
@yield('js_link')
<script type="text/javascript">
    $(document).ready(function() {
        App.init();
    });
</script>
@include('core::layouts.state_msg')
@yield('js_script')
