@extends('core::layouts.master')

@php
    $page_id = 'dormitory_dt';
    $page_name = 'รายงานหอพัก';
    $page_title = 'รายงานหอพัก';
    $dormitory_name = $dormitory->name;
    $dormitory_code = $dormitory->code;
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-5">
        <div class="card card-table" style="border-radius: 15px;">
            <div class="card-header text-center">รายงานหอพัก</div>
            <div class="card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('dormitorys.insert_comment', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="comment">คอมเมนต์</label>
                        <input type="text" name="comment" id="comment" class="form-control" value="" placeholder="เขียนคอมเมนต์" required>
                    </div>
                    <div class="form-group">
                        <label for="star">ดาว</label>
                        <select name="star" id="star" class="form-control" required>
                            <option value="" disabled selected>กรุณาให้คะแนน</option>
                            <option value=1>1 ดาว</option>
                            <option value=2>2 ดาว</option>
                            <option value=3>3 ดาว</option>
                            <option value=4>4 ดาว</option>
                            <option value=5>5 ดาว</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="mdi mdi-account-add"></i> บันทึก
                        </button>
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

@section('js_link')
    {{-- Custom JS links if needed --}}
@endsection

@section('js_script')

@endsection
