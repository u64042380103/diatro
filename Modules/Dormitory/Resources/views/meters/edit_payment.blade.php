@extends('core::layouts.master')

@php
    $page_id = 'dormitory_meters';
    $page_name = 'แก้ไขมิเตอร์';
    $page_title = 'แก้ไขมิเตอร์';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-header">แก้ไขมิเตอร์</div>
                <div class="card-body">
                    <div class="table-responsive noSwipe">
                        <form method="POST" action="{{ route('dormitorys.meters.update_payment', $datameter->id) }}">
                            @csrf
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <div class="tools">       
                                <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                                    <i class="mdi mdi-account-add"></i> บันทึก
                                </button>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:1%;"></th>
                                        <th style="width:7%;">หมายเลขห้อง</th>
                                        <th style="width:7%;">รหัส</th>
                                        <th style="width:5%;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>{{ $dataroom->name }}</td>
                                        <td>{{ $datameter->id}}</td>
                                        <td>
                                            <select name="payment_status" id="payment_status" class="form-control" required>
                                                <option value="Unpaid" {{ $datameter->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                                                <option value="Paid" {{ $datameter->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                                            </select>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="card-footer pt-5 pb-1"></div>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ url()->previous() }}">
            <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
        </a>
    </div>
@endsection

@section('css')
<style>
    /* Custom CSS if needed */
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
    {{-- coding js link --}}
@endsection

<script>
    function calculateUnit() {
        var previousMeter = parseFloat(document.getElementById('previous_meter').value);
        var currentMeter = parseFloat(document.getElementById('meter').value);
        
        if (!isNaN(previousMeter) && !isNaN(currentMeter) && previousMeter >= 0 && currentMeter >= 0) {
            var unitDifference = currentMeter - previousMeter;
            if (unitDifference < 0) {
                alert('ค่าเลขมิเตอร์ต้องไม่ต่ำกว่าค่าก่อนหน้า');
                document.getElementById('meter').value = previousMeter;
                document.getElementById('unit').value = 0;
            } else {
                document.getElementById('unit').value = unitDifference;
            }
        }
    }
</script>

