@extends('core::layouts.master')

@php
    // if (in_array(auth()->user()->user_type, [1,2,3])) {
    //     $page_id = 'dormitory_billings';
    //     $page_name = 'บิล/การเงิน';
    //     $page_title = 'บิล/การเงิน';
    // } else {
        $page_id = 'dormitory_rooms';
        $page_name = 'รายละเอียดห้องพัก';
        $page_title = 'รายละเอียดห้องพัก';
    // }
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-table">
                <div class="card-header">รวมใบแจ้งหนี้ ห้อง {{$dataroom->name}}</div>
                
                <div class="card-body">
                    <div>
                        <a href="{{ route('dormitorys.billings.insert', $dataroom->id) }}" class="btn btn-success btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-plus"></i> เพิ่ม...
                        </a>
                        <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบที่เลือก
                        </a>
                    </div>
                    <form id="combinedForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="settings" id="formAction">

                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        {{-- @if (in_array(auth()->user()->user_type, [1,2,3]))  --}}
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                        {{-- @endif --}}
                                    </th>
                                    <th width="10%">รายละเอียด</th>
                                    <th width="10%">รวม</th>
                                    <th width="10%">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($billingData as $item)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}">
                                            <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('dormitorys.billings_month.index', $item->id) }}" >
                                            {{$item->id}}
                                        </a>
                                    </td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{$item->payment_status == 'Unpaid' ? 'ค้างจ่าย' : 'จ่ายแล้ว'}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">ไม่พบข้อมูล</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <a href="{{ $previousUrl }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
                <i class="mdi mdi-undo"></i> ย้อนกลับ
            </a>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .invoice-date {
            text-align: right;
            margin-right: 20px;
            margin-top: 10px;
        }
        .invoice-status {
            text-align: right;
            margin-right: 20px;
            margin-top: 10px;
            font-weight: bold;
        }
        .dormitory-info {
            font-size: 1.25em;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media print {
            .card-header, .btn, .tools {
                display: none;
            }
        }
    </style>
@endsection

@section('js_link')
    {{-- coding js link --}}
@endsection

@section('js_script')
<script>
    document.getElementById('checkAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.disabled) {
                checkbox.checked = document.getElementById('checkAll').checked;
            }
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
        e.preventDefault();

        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (!selectedCheckboxCount) {
            alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ');
            return;
        }

        if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
            document.getElementById('combinedForm').action = "{{ route('dormitorys.billings.massDelete') }}";
            document.getElementById('combinedForm').submit();
        }
    });

    document.getElementById('settings').addEventListener('click', function(e) {
        e.preventDefault();
        
        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (selectedCheckboxCount === 0) {
            alert('ไม่พบข้อมูล');
            return;
        }
        document.getElementById('formAction').value = 'settings';
        document.getElementById('combinedForm').action = "{{ route('dormitorys.billings.settings', $dataroom->id) }}";
        document.getElementById('combinedForm').submit();
    });

    document.getElementById('showBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('combinedForm').action = "{{ route('dormitorys.billings.show', $dataroom->id) }}";
        document.getElementById('combinedForm').submit();
    });

    document.getElementById('changeBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('combinedForm').action = "{{ route('dormitorys.billings.change_billings', $dataroom->id) }}";
        document.getElementById('combinedForm').submit();
    });
</script>
@endsection
