@extends('core::layouts.master')
@php
    $page_id='users_group';
    $page_name=$usergroup->note;
    $page_title=$usergroup->note;
@endphp
@section('content')

<div class="row">
    <div class="col-sm-12">
      <div class="card card-table">
        <div class="card-header">{{$usergroup->note}}
            <div class="tools">
                <a href="{{ route('usersgroup.add') }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                    <i class="mdi mdi-account-add"></i> เพิ่มผู้ใช้
                </a>
                <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                    <i class="mdi mdi-delete"></i> ลบที่เลือก
                </a>
            </div>
        </div>
        <div class="card-body">
          <div class="table-responsive noSwipe">
            <form id="deleteForm" action="{{ route('usersgroup.massout') }}" method="POST">
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
                            <th>ชื่อ</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>เบอร์โทร</th>
                            <th></th>              
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_lavel as $item)
                        <tr>
                            <td>
                                <div class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" name="ids[]" type="checkbox" value="{{ $item->id }}" id="checkbox{{ $item->id }}" @if ($item->username == 'admin') disabled @endif>
                                    <label class="custom-control-label" for="checkbox{{ $item->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->username }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->user->phone }}</td>
                            <td class="text-right">
                                <a href="{{ route('usersgroup.out', $item->id) }}" class="btn btn-warning btn-rounded text-dark" title="แก้ไขกลุ่มผู้ใช้งาน">
                                    <i class="mdi mdi-wrench"></i> ลบ
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
      </div>
      <a href="{{ route('usersgroup.index') }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
        <i class="mdi mdi-undo"></i> ย้อนกลับ
    </a>
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
        if (!checkbox.disabled) { // Only check/uncheck if the checkbox is not disabled
            checkbox.checked = this.checked;
        }
    }, this);
});

document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
    e.preventDefault();

    var selectedCheckboxCount = document.querySelectorAll('.custom-control-input:checked:not(#checkAll)').length;

    if (selectedCheckboxCount === 0) {
        alert('กรุณาเลือกกลุ่มผู้ใช้งานที่ต้องการลบ');
        return;
    }

    if (confirm('คุณต้องการลบกลุ่มผู้ใช้งานที่เลือกหรือไม่ ?')) {
        document.getElementById('deleteForm').submit();
    }
});

</script>
@endsection