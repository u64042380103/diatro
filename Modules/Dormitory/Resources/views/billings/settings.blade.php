@extends('core::layouts.master')

@php
    $page_id = 'dormitory_billings';
    $page_name = 'บิล/การเงินที่เลือก';
    $page_title = 'บิล/การเงินที่เลือก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-table">
                <div class="card-header">บิลการเงินที่เลือก</div>
                <div class="card-body">
                    <div>
                        <a href="#" class="btn btn-primary btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                            <i class=""></i> บันทึก
                        </a>
                        <div style="float: right; display: flex; align-items: center; margin-right: 20px;">
                            <span style="margin-right: 10px;">แก้ไขทั้งหมด</span>
                            <select name="payment_status_All" id="payment_status_All" class="form-control" style="width: 150px;" required>
                                <option value="Unpaid">ค้างจ่าย</option>
                                <option value="Paid">จ่ายแล้ว</option>
                            </select>
                        </div>
                    </div>
                        <form id="pirntForm" action="{{ route('dormitorys.billings.settings_update', $dataroom->id) }}" method="POST">
                            @csrf
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th width="1%">
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkAll"checked>
                                                <label class="custom-control-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th width="10%">รายละเอียด</th>
                                        <th width="10%">หน่วยที่ใช้</th>
                                        <th width="10%">หน่วยละ</th>
                                        <th width="10%">รวม</th>
                                        <th width="10%">สถานะ </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalLease = 0;
                                    @endphp
                                    @foreach ($selectedWaterMeters as $water)
                                    @php
                                        $useunitWater = $water->unit ?? 0;
                                        if ($useunitWater <= 10) {
                                            $unitWater = 16.00;
                                        } elseif ($useunitWater <= 20) {
                                            $unitWater = 19;
                                        } elseif ($useunitWater <= 30) {
                                            $unitWater = 20;
                                        } elseif ($useunitWater <= 50) {
                                            $unitWater = 21.50;
                                        } elseif ($useunitWater <= 80) {
                                            $unitWater = 21.60;
                                        } elseif ($useunitWater <= 100) {
                                            $unitWater = 21.65;
                                        } elseif ($useunitWater <= 300) {
                                            $unitWater = 21.70;
                                        } elseif ($useunitWater <= 1000) {
                                            $unitWater = 21.75;
                                        } elseif ($useunitWater <= 2000) {
                                            $unitWater = 21.80;
                                        } elseif ($useunitWater <= 3000) {
                                            $unitWater = 21.85;
                                        } else {
                                            $unitWater = 21.90;
                                        }
                                        $totalWater = $useunitWater * $unitWater;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $water->id }}" id="checkbox{{ $water->id }}" data-amount="{{ $totalWater }}" checked>
                                                <label class="custom-control-label" for="checkbox{{ $water->id }}"></label>
                                            </div>
                                        </td>
                                        <td>น้ำ ({{ date('d-m-Y', strtotime($water->created_at)) }})</td>
                                        <td>{{ $useunitWater }} </td>
                                        <td>{{ $unitWater }}</td>
                                        <td>{{ number_format($totalWater, 2) }} บาท </td>
                                        <td>
                                            <select name="payment_status_{{ $water->id }}" id="payment_status_{{ $water->id }}" required>
                                                <option value="Paid" {{ $water->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                                                <option value="Unpaid" {{ $water->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach ($selectedElectricMeters as $electric)
                                @php
                                    $useunitElectric = $electric->unit ?? 0;
                                    if ($useunitElectric <= 15) {
                                        $unitElectric = 2.3488;
                                    } elseif ($useunitElectric <= 25) {
                                        $unitElectric = 2.9882;
                                    } elseif ($useunitElectric <= 35) {
                                        $unitElectric = 3.2405;
                                    } elseif ($useunitElectric <= 100) {
                                        $unitElectric = 3.6237;
                                    } elseif ($useunitElectric <= 150) {
                                        $unitElectric = 3.7171;
                                    } elseif ($useunitElectric <= 400) {
                                        $unitElectric = 4.2218;
                                    } else {
                                        $unitElectric = 4.4217;
                                    }
                                    $totalElectric = $useunitElectric * $unitElectric;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $electric->id }}" id="checkbox{{ $electric->id }}" data-amount="{{ $totalElectric }}" checked>
                                            <label class="custom-control-label" for="checkbox{{ $electric->id }}"></label>
                                        </div>
                                    </td>
                                    <td>ไฟฟ้า ({{ date('d-m-Y', strtotime($electric->created_at)) }})</td>
                                    <td>{{ $useunitElectric }} </td>
                                    <td>{{ $unitElectric }}</td>
                                    <td>{{ number_format($totalElectric, 2) }} บาท </td>
                                    <td>
                                        <select name="payment_status_{{ $electric->id }}" id="payment_status_{{ $electric->id }}" required>
                                            <option value="Paid" {{ $electric->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                                            <option value="Unpaid" {{ $electric->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @foreach ($Lease as $Lease_fee)
                                @php
                                    $totalLease += $Lease_fee->monthly_rent;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $Lease_fee->id }}" id="checkbox{{ $Lease_fee->id }}" data-amount="{{ $Lease_fee->monthly_rent }}" checked>
                                            <label class="custom-control-label" for="checkbox{{ $Lease_fee->id }}"></label>
                                        </div>
                                    </td>
                                    <td>ค่าเช่า ({{ date('d-m-Y', strtotime($Lease_fee->created_at)) }})</td>
                                    <td> </td>
                                    <td></td>
                                    <td>{{ number_format($Lease_fee->monthly_rent, 2) }} บาท </td>
                                    <td>
                                        <select name="payment_status_{{ $Lease_fee->id }}" id="payment_status_{{ $Lease_fee->id }}" required>
                                            <option value="Paid" {{ $Lease_fee->payment_status == 'Paid' ? 'selected' : '' }}>จ่ายแล้ว</option>
                                            <option value="Unpaid" {{ $Lease_fee->payment_status == 'Unpaid' ? 'selected' : '' }}>ค้างจ่าย</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                                    <tr>
                                        <td></td>
                                        <td>ยอดรวม</td>
                                        <td></td>
                                        <td id="selectedIds"></td>
                                        <td></td>
                                        <td id="totalAmount"></td>
                                        <td ></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                </div>
            </div>
            <div>
                <a href="{{ route('dormitorys.billings.show', $dataroom->id) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
                    <i class="mdi mdi-undo"></i> ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    input, select {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        width: 150px;
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
        margin: 5px auto; /* Center the card horizontally */
    }
    .card-header {
        text-align: center;
    }
    .card-body {
        padding: 20px; /* Add padding to card body */
    }
    .invoice-date, .invoice-status, .dormitory-info {
        text-align: right;
        margin-right: 20px;
        margin-top: 10px;
    }
    .invoice-status {
        font-weight: bold;
    }
    .dormitory-info {
        font-size: 1.25em;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }
    #selectedIds {
        display: none;
    }
</style>

@endsection

@section('js_script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateSelectedIds() {
        var selectedIds = [];
        var totalAmount = 0;
        var checkboxes = document.querySelectorAll('.custom-control-input[name="ids[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            selectedIds.push(checkbox.value);
            totalAmount += parseFloat(checkbox.getAttribute('data-amount'));
        });
        document.getElementById('selectedIds').innerText = selectedIds.join(', ');
        document.getElementById('totalAmount').innerText = totalAmount.toFixed(2) + ' บาท';
    }

    document.getElementById('checkAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.disabled) {
                checkbox.checked = document.getElementById('checkAll').checked;
            }
        });
        updateSelectedIds();
    });

    document.querySelectorAll('.custom-control-input[name="ids[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            updateSelectedIds();
        });
    });

    document.getElementById('payment_status_All').addEventListener('change', function() {
        var selectedValue = this.value;
        var statusSelects = document.querySelectorAll('select[id^="payment_status_"]');
        statusSelects.forEach(function(select) {
            select.value = selectedValue;
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault();
        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (selectedCheckboxCount === 0) {
            alert('โปรดเลือกบิลที่ต้องการบันทึก');
            return;
        }
        document.getElementById('pirntForm').submit();
    });

    updateSelectedIds();
});

</script>
@endsection
