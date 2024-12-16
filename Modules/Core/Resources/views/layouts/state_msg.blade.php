@if(session("ok_msg"))
<script>
    $(document).ready(function(){
        $.gritter.add({
            title: 'Done.',
            text: '{{session("ok_msg")}}',
            class_name: 'color success'
        });
    });
</script>
@php session()->forget('ok_msg'); @endphp
@endif

@if(session('err_msg'))
<script>
    $(document).ready(function(){
        $.gritter.add({
            title: 'Error: ',
            text: '{{session('err_msg')}}',
            class_name: 'color danger'
        });
    });
</script>
@php session()->forget('err_msg'); @endphp
@endif