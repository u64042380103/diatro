@extends('core::layouts.master')

@php
    $page_id = 'users';
    $page_name = 'แก้ไขคอมเมนต์';
    $page_title = 'แก้ไขคอมเมนต์';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">แก้ไขคอมเมนต์</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('users_review.update', $users_review->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $users_review->user->name }}" maxlength="50" readonly required>
                        <input type="hidden" name="users_id" id="users_id" value="{{ $users_review->users_id }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="dormitorys_id">หอพัก</label>
                        <input type="text" name="dormitorys_name" id="dormitorys_name" class="form-control" value="{{ $users_review->dormitory->name }}" placeholder="กรุณาใส่ชื่อหอพัก" maxlength="50" readonly required>
                        <input type="hidden" name="dormitorys_id" id="dormitorys_id" value="{{ $users_review->dormitorys_id }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="star">ดาว</label>
                        <select name="star" id="star" class="form-control" required>
                            <option value=1 {{ $users_review->star == 1 ? 'selected' : '' }}>1 ดาว</option>
                            <option value=2 {{ $users_review->star == 2 ? 'selected' : '' }}>2 ดาว</option>
                            <option value=3 {{ $users_review->star == 3 ? 'selected' : '' }}>3 ดาว</option>
                            <option value=4 {{ $users_review->star == 4 ? 'selected' : '' }}>4 ดาว</option>
                            <option value=5 {{ $users_review->star == 5 ? 'selected' : '' }}>5 ดาว</option>
                        </select>
                        @error('star')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group text-right">
                        <label></label>
                        <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-account-add"></i> อัปเดต
                        </button>
                        <a href="{{ route('users_review.delete', $users_review->id) }}" class="btn btn-danger btn-rounded" style="float:right; margin-right: 20px;">
                            <i class="mdi mdi-delete"></i> ลบ
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-rounded text-dark" style="float:right; margin-right: 20px;">
            <i class="mdi mdi-undo"></i> ย้อนกลับ
        </a>
    </div>
</div>
@endsection

@section('css')
<style>
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

@section('js_script')

@endsection
