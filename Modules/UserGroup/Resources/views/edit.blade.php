@extends('core::layouts.master')
@php
    $page_id='users_group';
    $page_name='ข้อมูลกลุ่มผู้ใช้';
    $page_title='ข้อมูลกลุ่มผู้ใช้';
@endphp
@section('content')
<div class="row justify-content-center">
  <div class="col-sm-8 col-md-5">
      <div class="card card-table" style="border-radius: 15px;">
          <div class="card-header text-center">{{$usergroup->name}}</div>
          <div class="card-body" style="padding: 20px;">
              <form method="POST" action="{{route('usersgroup.update',$usergroup->id)}}">
                  @csrf
                  
                  <div class="form-group">
                      <label for="name">ชื่อกลุ่มผู้ใช้</label>
                      <input type="text" name="name" id="name" value="{{$usergroup->name}}" maxlength="50" required>
                    </div>

                  <div class="form-group">
                      <label for="note">หมายเหตุ</label>
                      <input type="text" name="note" id="note" value="{{$usergroup->note}}" maxlength="50" required>
                  </div>
                  <div class="form-group">
                      <label for="status">สถานะ</label>
                      <select name="status" id="status" required>
                        <option value="Enable" {{ $usergroup->status_delete == 'Enable' ? 'selected' : '' }}>Enable</option>
                        <option value="Disable" {{ $usergroup->status_delete == 'Disable' ? 'selected' : '' }}>Disable</option>
                    </select>
                      @if ($errors->has('room_id'))
                          <div class="text-danger">{{ $errors->first('room_id') }}</div>
                      @endif
                  </div>
                  <div class="form-group text-right">
                      <label></label>
                      <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                        <i class="mdi mdi-account-add"></i> อัปเดต
                      </button>
                        {{-- <a href='{{route('usersgroup.delete',$usergroup->id)}}' class="btn btn-danger btn-rounded " onclick="return confirm('คุณต้องการลบกลุ่ม {{$usergroup->name}} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                          <i class="mdi mdi-delete"></i> ลบกลุ่มผู้ใช้ {{$usergroup->name}}
                        </a> --}}
                  </div>
              </form>
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
    {{-- coding js link--}}
@endsection

@section('js_script')
    {{-- coding js script--}}
@endsection