@extends('core::layouts.master')
@php
    $page_id='dormitory_lease_agreements';
    $page_name='สัญญาเช่า';
    $page_title='สัญญาเช่า';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">รายชื่อผู้ใช้งาน</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.lease_agreements.insert', $dormitory->code) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                    <div class="form-group">
                        <label for="rooms_id">ห้อง</label>
                        <input type="text" name="" id="" class="form-control" value="{{$data_room->name}}" readonly required>
                        <input type="hidden" name="rooms_id" id="rooms_id" class="form-control" value="{{$data_room->id}}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="username">ผู้ทำสัญญาเช่า</label>
                        <input type="text" name="username" id="username" class="form-control" required placeholder="กรุณาใส่ชื่อผู้ทำสัญญาเช่า">
                        <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ old('user_id') }}">
                    </div>
                    <div class="form-group">
                        <label for="startDate">วันเริ่มเช่า</label>
                        <input type="text" name="startDate" id="startDate" class="form-control datepicker" value="" placeholder="กรุณาเลือกวันเริ่มเช่า" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="endDate">วันสินสุดการเช่า</label>
                        <input type="text" name="endDate" id="endDate" class="form-control datepicker" value="" placeholder="กรุณาเลือกวันสิ้นสุด" required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_rent">ราคาค่าเช่า</label>
                        <input type="number" name="monthly_rent" id="monthly_rent" class="form-control" value="{{$data_room->monthly_rent}}" placeholder="กรุณาใส่ราคาค่าเช่า" required>
                    </div>
                    <div class="form-group">
                        <label for="deposit">มัดจำ</label>
                        <input type="number" name="deposit" id="deposit" class="form-control" value="{{$data_room->deposit}}" placeholder="กรุณาใส่ค่ามัดจำ"required>
                    </div>
                    <div class="form-group">
                        <label for="img">หลักฐานสัญญาเช่า</label>
                        {{-- @if($data->img)
                        <div class="mb-3">
                            <img src="{{ asset('storage/'. $data->img) }}" id="img-preview" style="max-width: 100%; height: auto;">
                        </div>
                    @endif --}}
                    <img src="" id="img-preview" style="width: 50px; height: 50px;  display: none; margin-bottom: 10px;">
                    <input class="inputfile" id="img" type="file" name="img" accept="image/*" onchange="previewImage(event)">
                    <label class="btn-secondary" id="btnImg" for="img"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพ . . . . .</span>
                        {{-- <input type="file" name="img" id="img" class="form-control" accept="image/*"> --}}
                    </div>
                    <div class="form-group">
                        <label for="payment_status">สถานะ</label>
                        <select name="payment_status" id="payment_status" class="form-control" required>
                            <option value="Unpaid">ค้างจ่าย</option>
                            <option value="Paid">จ่ายแล้ว</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
        
    </div>

    <br>
        <a href="{{ route('dormitorys.lease_agreements.index', $dormitory->code) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
  
    .btn-primary {
        margin-right: 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .btn-primary {
        margin-right: 10px;
    }
    .card {
        margin: 5px auto;
    }
    .card-header {
        text-align: center;
    }
    .card-body {
        padding: 20px;
    }
    label {
        font-weight: bold;
    }
</style>
@endsection

@section('js_link')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.th.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('js_script')
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('form').on('submit', function() {
            $('.datepicker').each(function() {
                var date = $(this).datepicker('getDate');
                $(this).val(moment(date).format('YYYY-MM-DD'));
            });
        });
    });
document.getElementById('username').addEventListener('blur', function() {
    var username = this.value;
    if (username) {
        fetch('{{ route("dormitorys.lease_agreements.fetchUser") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({username: username})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('username').value = data.username;
                document.getElementById('user_id').value = data.user_id;
            } else {
                alert('ไม่พบข้อมูลในระบบ');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('img-preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);

        document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
        document.getElementById("btnImg").classList.add('btn-success');
    }

</script>
@endsection
