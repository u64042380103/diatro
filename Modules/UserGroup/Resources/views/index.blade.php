@extends('core::layouts.master')
@php
    $page_id='users_group';
    $page_name='กลุ่มผู้ใช้งาน';
    $page_title='กลุ่มผู้ใช้งาน';
@endphp
@section('content')

<div class="row">
    <div class="col-sm-12">
      <div class="card card-table">
        <div class="card-header">กลุ่มผู้ใช้งาน
            {{-- <div class="tools">
                <a href="{{ route('usersgroup.create') }}" class="btn btn-primary btn-rounded" style="float:right; margin-right: 10px;">
                    <i class="mdi mdi-account-add"></i> สร้างกลุ่มผู้ใช้
                </a>
                <a href="#" class="btn btn-danger btn-rounded" id="deleteSelectedBtn" style="float:right; margin-right: 20px;">
                    <i class="mdi mdi-delete"></i> ลบที่เลือก
                </a>
            </div> --}}
        </div>
        <div class="card-body">
          <div class="table-responsive noSwipe">
            <form id="deleteForm" action="{{ route('usersgroup.massDelete') }}" method="POST">
              @csrf
              @method('DELETE')
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th style="width:4%;"></th>
                    <th>ชื่อ</th>
                    <th>หมายเหตุ</th>
                    <th>สถานะ</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($usergroup as $item)
                  <tr>
                    <td></td>
                    <td>
                      <a href="{{ route('usersgroup.show', $item->id) }}">
                        {{ $item->name }} 
                      </a>
                      {{-- {{$item->id}} --}}
                    </td>
                    <td>{{ $item->note }}</td>
                    <td class="{{ $item->status_delete == 'Enable' ? 'text-success' : 'text-danger' }}">{{ $item->status_delete }}</td>
                    <td class="text-right">
                        <a href="{{ route('usersgroup.edit', $item->id) }}" class="btn btn-warning btn-rounded text-dark" title="แก้ไขกลุ่มผู้ใช้งาน">
                            <i class="mdi mdi-wrench"></i> แก้ไข
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
        <div class="card-footer pt-5 pb-1">
          {{ $usergroup->links('pagination::bootstrap-5') }}
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