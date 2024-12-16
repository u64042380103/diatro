@extends('core::layouts.master')

@php
    $page_id = 'dormitory_rooms';
    $page_name = 'อุปกรณ์';
    $page_title = 'อุปกรณ์';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">ระบบควบคุมอุปกรณ์
                <div class="tools">
                    <a href="{{ route('device.create',$room->name) }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                        <i class="mdi mdi-account-add"></i> เพิ่มอุปกรณ์
                    </a>
                    <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-delete"></i> ลบอุปกรณ์ที่เลือก
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <form id="deleteForm" action="{{ route('device.massDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width:4%;">
                                        <div class="custom-control custom-control-sm custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="checkAll">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>ชื่ออุปกรณ์</th>
                                    <th>รหัสอุปกรณ์</th>
                                    <th>พอร์ต</th>
                                    <th>สถานะ</th>
                                    <th>ตั้งค่าเวลาทำงาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}" @if ($item->username == 'admin') disabled @endif>
                                                <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->device_id }}</td>
                                        <td>{{ $item->port }}</td>
                                        <td>
                                            @if ($item->status == '1')
                                                <span class="text-success">กำลังทำงาน</span>
                                            @else
                                                <span class="text-danger">หยุดทำงาน</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- {{$item->getsettime->first()->set_times}} --}}
                                            @php
                                                $isSetTime = $item->set_times ?? false;
                                            @endphp
                                            @if ($isSetTime)
                                                <a href="{{ route('device.set_time', $item->id) }}" class="btn btn-success btn-rounded text-light">
                                                    <i class="mdi mdi-alarm"></i> ตั้งค่าเวลาทำงาน
                                                </a>
                                            @else
                                                <a href="{{ route('device.set_time', $item->id) }}" class="btn btn-danger btn-rounded text-light">
                                                    <i class="mdi mdi-alarm-off"></i> ไม่ได้ตั้งค่าเวลาทำงาน                                                </a>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if ($item->set_time)
                                                {{ $item->set_time->on_off }}
                                            @else
                                                ไม่พบข้อมูล
                                            @endif
                                        </td> --}}
                                        <td class="text-right">
                                            <a href="{{ route('device.settings', $item->id) }}" class="btn btn-warning btn-rounded text-dark">
                                                <i class="mdi mdi-wrench"></i> ตั้งค่าอุปกรณ์
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            ไม่พบรายการ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            {{-- <div class="card-footer pt-5 pb-1">{{ $view->links('pagination::bootstrap-5') }}</div> --}}
            <div class="mt-2">
                <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $previousUrl }}">
                    <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .pagination {
        display: flex;
        justify-content: center;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link {
        color: #c0c0c0;
    }
    .page-link:hover {
        color: #0056b3;
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
        e.preventDefault(); // Prevent default form submission behavior

        // Check if any checkboxes are selected
        var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked').length;
        if (!selectedCheckboxCount) {
            alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ'); // Prompt user to select items
            return;
        }

        // Confirmation prompt before submission
        if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
            // If confirmed, submit the form with the selected IDs
            document.getElementById('deleteForm').submit();
        }
    });
</script>
@endsection
