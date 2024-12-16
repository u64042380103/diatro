@extends('core::layouts.master')
@php
    $page_id = 'dormitory_check_out';
    $page_name = 'เช็คอินรอย้ายเข้า';
    $page_title = 'เช็คอินรอย้ายเข้า';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp
@section('content')
<div class="p-5 mt-n5 mb-3 mx-n5 text-dark" style="background-attachment: fixed; height: 250px; background-color: #333333;">
    <div class="text-center" style="font-size: 20px; color: #ffffff;">ผังห้องพัก</div>
    <div class="mt-1 text-center">
        <div class="mt-1 mb-3 text-center">
            <a href="{{ route('dormitorys.show', $dormitory_code) }}" style="font-size: 26px; color: #ffffff;">
                <i class="icon mdi mdi-city-alt"></i> {{ $dormitory->name }}
            </a>
        </div>
    </div>
</div>
<div style="margin-top: -155px;">
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <div class="input-group input-search input-group-sm" style="border-radius: 15px; color: #ffffff;">
                <form action="" method="GET" style="display: flex; width: 100%;">
                    <input class="form-control searchbox" type="text" name="search" placeholder="ค้นหา . . ." style="border-radius: 15px;" value="{{ request()->query('search') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="submit" style="border-radius: 15px;">
                            <i class="icon mdi mdi-search"></i>
                        </button>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card shadow" style="margin-top: 15px; border-radius: 15px; background-color: #FBFBFB;">
    <div class="card-body text-dark">
            <form id="deleteForm" action="#" method="POST">
                @csrf
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:4%;">
                                <div class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>ชื่อ</th>
                            <th>ห้อง</th>
                            <th>เบอร์ติดต่อ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>
                                    <div class="custom-control custom-control-sm custom-checkbox">
                                        <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}">
                                        <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td >
                                    {{-- <a class="icon black-icon" href="{{ route('users_review.index', ['id' => $item->users_id, 'dormitory_user' => true,'room_id' => $item->room_id]) }}"> --}}
                                        {{ $item->user->username }}
                                    {{-- </a> --}}
                                </td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->rooms->name }}</td>
                                <td>{{ $item->user->phone }}</td>
                                <td class="text-right">
                                    <a href="{{ route('dormitorys.check_out.change', $item->id) }}" class="btn btn-danger btn-rounded" role="button">
                                        ย้ายออก
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="10">ไม่พบรายการ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
    </div>
</div>
<a href="{{ route('dormitorys.check_out.index',$dormitory->code) }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
    <i class="mdi mdi-undo"></i> ย้อนกลับ
</a>
@endsection

@section('css')
    {{-- coding css --}}
@endsection

@section('js_link')
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
    <script>
        document.getElementById('checkAll').addEventListener('click', function() {
    var checkboxes = document.querySelectorAll('.custom-control-input:not(#checkAll)');
    checkboxes.forEach(function(checkbox) {
        if (!checkbox.disabled) {
            checkbox.checked = document.getElementById('checkAll').checked;
        }
    });
});
</script>
@endsection