@extends('core::layouts.master')
@php
    $page_id = 'dormitory_users';
    $page_name = 'รายชื่อผู้ใช้งาน';
    $page_title = 'รายชื่อผู้ใช้งาน';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-table">
                <div class="card-header">
                    <div class="tools">
                        <form method="GET" action="{{ route('dormitorys.users.index', $dormitory_code) }}">
                            <div class="input-group input-search input-group-sm" style="border-radius: 15px;">
                                <input class="form-control searchbox" type="text" name="search" placeholder="ค้นหา . . ." value="{{ request()->query('search') }}" style="border-radius: 15px;">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="submit" style="border-radius: 15px;">
                                        <i class="icon mdi mdi-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div style="font-size:18px;">รายชื่อผู้ใช้งาน</div>
                    <div class="mt-2">
                        {{-- @if(in_array(auth()->user()->user_type, [1, 2,3])) --}}
                        {{-- <a href="{{ route('dormitorys.users.create', $dormitory->code) }}" class="btn btn-success btn-rounded">
                            <i class="mdi mdi-plus"></i> เพิ่มผู้ใช้งาน
                        </a> --}}
                        <a href="" role="button" onclick="create(); return false;" class="btn btn-success btn-rounded" title="เพิ่มผู้ใช้">
                            <i class="mdi mdi-plus"></i> เพิ่มผู้ใช้งาน
                        </a>
                        {{-- @if(auth()->user()->user_type != 3) --}}
                            <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn">
                                <i class="mdi mdi-delete"></i> ลบที่เลือก
                            </a>
                        {{-- @endif
                        @endif --}}
                    </div>
                </div>
                <div class="card-body">
                    <form id="deleteForm" action="{{ route('dormitorys.users.massDelete') }}" method="POST">
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
                                    <th>ชื่อผู้ใช้งาน</th>
                                    <th>ชื่อ</th>
                                    <th>ห้อง</th>
                                    <th>เบอร์ติดต่อ</th>
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
                                        <td>
                                            <a class="icon black-icon" href="{{ route('users_review.index', ['id' => $item->users_id, 'dormitory_user' => true, 'room_id' => $item->room_id, 'user' => $item->id]) }}">
                                                {{ $item->user->username }}
                                            </a>
                                        </td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>
                                            {{ $item->room_id}}
                                        </td>
                                        <td>{{ $item->user->phone }}</td>
                                        <td class="text-right"></td>
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
                <div class="card-footer pt-5 pb-1">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade colored-header colored-header-primary" id="modal-create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เพิ่มผู้ใช้</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('dormitorys.users.insert',$dormitory->code)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <label for="username">ชื่อผู้ใช้</label>
                                <input type="text" name="username" id="username" placeholder="กรุณาใส่ชื่อผู้ใช้" value="{{ old('username') }}" class="form-control" required>
                                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
                                @if ($errors->has('username'))
                                    <div class="text-danger">{{ $errors->first('username') }}</div>
                                @endif
                            </div>
                            <input type="hidden" name="dormitorys_id" id="dormitorys_id" value="{{$dormitory->id}}">
                            <div class="modal-body">
                                <label for="room_id">รหัสห้อง</label>
                                <select name="room_id" id="room_id" class="form-control">
                                    <option value="">กรุณาเลือกห้อง</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('room_id'))
                                    <div class="text-danger">{{ $errors->first('room_id') }}</div>
                                @endif
                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="mdi mdi-account-add"></i> บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
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
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
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
    .black-icon {
        color: rgb(100, 100, 100);
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
                alert('กรุณาเลือกผู้ใช้ที่ต้องการลบ');
                return;
            }

            if (confirm('คุณต้องการลบผู้ใช้ที่เลือกหรือไม่ ?')) {
                document.getElementById('deleteForm').submit();
            }
        });
    function create() {
        $("#modal-create").modal("show");

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

    }
    document.getElementById('username').addEventListener('blur', function() {
        var username = this.value;
            if (username) {
        fetch('{{ route("dormitorys.users.fetchUser") }}', {
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
            const output = document.getElementById('imgpro-preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
            // Update button text and style
            document.getElementById("nameImg").textContent = 'เลือกรูปภาพสำเร็จ';
            document.getElementById("btnImg").classList.add('btn-success');
    }

    </script>
@endsection
