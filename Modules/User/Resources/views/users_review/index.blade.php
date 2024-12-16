@extends('core::layouts.master')

@php
    if ($dormitoryUser) {
    $page_id='dormitory_users';
    $page_name='ข้อมูลผู้ใช้งาน';
    $page_title='ข้อมูลผู้ใช้งาน';
    $dormitory_name=$dormitory->name;
    $dormitory_code=$dormitory->code;
    }
    else{
    $page_id = 'users';
    $page_name = 'ข้อมูลผู้ใช้งาน';
    $page_title = 'ข้อมูลผู้ใช้งาน';
    }

    use Carbon\Carbon;
@endphp

@section('content')
<div class="row">
    <div class="main-content container-fluid">
        <div class="user-profile">
            <div class="row">
                <div class="col-lg-5">
                    <div class="user-display user-display-container" style="border-radius: 15px;">
                        <div class="user-display-bottom">
                            <div class="">
                                <a href="" role="button" onclick="changeImg(); return false;">
                                    <img id="sh_img" src="{{ asset('storage/' . $user->imgpro) }}" style="width: 50px; height: 50px; border-radius: 50%; display: block; margin-bottom: 10px;">
                                </a>
                                <div class="name">{{ $user->name }}</div>
                                <div class="nick"></div>
                            </div>
                            <div class="row user-display-details">
                                <div class="col-4">
                                    <div class="title">ชื่อผู้ใช้</div>
                                    <div class="counter">{{ $user->username }}</div>
                                </div>
                                @if($dormitoryUser)
                                    <div class="col-4">
                                        <div class="title">หอพัก</div>
                                        <div class="counter">{{$dormitory->name}}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="title">ห้อง</div>
                                        <div class="counter">{{$room->name}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="widget-head">
                            <form id="deleteForm" action="{{ route('users_review.massDelete') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="card card-table" style="border-radius: 15px;">
                                    <div class="card-body" style="padding: 0;">
                                        <div class="table-responsive noSwipe">
                                            <table class="no-border no-strip skills">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        @foreach ($errors->all() as $error)
                                                            <div>{{ $error }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @php
                                                    $sum = \Modules\User\Entities\Modules_User_review::where('status_delete', '!=', 'Disable')->where('users_id', $id)->sum('star');
                                                    $count = \Modules\User\Entities\Modules_User_review::where('status_delete', '!=', 'Disable')->where('users_id', $id)->count();
                                                    $average = $count > 0 ? $sum / $count : 0;
                                                @endphp
                                                <div class="card-header card-header-divider d-flex justify-content-between">
                                                        <span>รีวิวจากหอพัก</span>
                                                        <span>ค่าเฉลี่ย: {{$average}} <i class="mdi mdi-star"></i></span>
                                                        <div>
                                                            @if($dormitoryUser)
                                                                @php
                                                                    $userReview = \Modules\User\Entities\Modules_User_review::where('users_id', $id)
                                                                        ->where('dormitorys_id', $dormitory->id)
                                                                        ->where('status_delete', '!=', 'Disable')
                                                                        ->first();
                                                                @endphp
                                                                @if ($userReview)
                                                                <div class="tools">
                                                                    {{-- <a href="{{ route('users_review.edit', $id) }}" class="btn btn-warning btn-rounded" title="แก้ไข">
                                                                        <i class="mdi mdi-settings"></i>
                                                                    </a> --}}
                                                                    <a href="" role="button" onclick="review_edit(); return false;" class="btn btn-warning btn-rounded" title="แก้ไข">
                                                                        <i class="mdi mdi-settings"></i>
                                                                    </a>
                                                                </div>
                                                                @else
                                                                <div class="tools">
                                                                    {{-- <a href="{{ route('users_review.create', ['id' => $user->id, 'dormitory_user' => true,'dormitory_id' => $dormitory->id]) }}" class="btn btn-primary btn-rounded" title="เพิ่มคอมเมนต์">
                                                                        <i class="mdi mdi-edit"></i> เพิ่ม
                                                                    </a> --}}
                                                                    <a href="" role="button" onclick="review_create(); return false;" class="btn btn-primary btn-rounded" title="เพิ่มคอมเมนต์">
                                                                        <i class="mdi mdi-edit"></i> เพิ่ม
                                                                    </a>
                                                                </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                </div>
                                                <tbody class="no-border-x no-border-y">
                                                    @forelse ($review as $item)
                                                        <tr>
                                                            <td></td>
                                                            <td>{{ $item->dormitory->name }} : {{ $item->star }} <i class="mdi mdi-star"></i> </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="5">
                                                                ไม่พบรายการ
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="widget-head">
                            <form id="deleteForm" action="{{ route('users_review.massDelete') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="card card-table" style="border-radius: 15px;">
                                    <div class="card-body" style="padding: 0;">
                                        <div class="table-responsive noSwipe">
                                            <table class="no-border no-strip skills">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        @foreach ($errors->all() as $error)
                                                            <div>{{ $error }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="card-header card-header-divider d-flex justify-content-between">
                                                    <span>คอมเมนต์</span>
                                                        <div class="tools">
                                                            {{-- <a href="{{ route('users_Comment.create', $id) }}" class="btn btn-rounded" title="เพิ่มคอมเมนต์">
                                                                <i class="mdi mdi-plus"></i>
                                                            </a> --}}
                                                            <a href="" role="button" onclick="Comment(); return false;" class="btn btn-rounded" title="เพิ่มคอมเมนต์">
                                                                <i class="mdi mdi-plus"></i> 
                                                            </a>
                                                        </div>
                                                </div>
                                                <tbody class="no-border-x no-border-y">
                                                    @forelse ($comments as $data)
                                                        <tr>
                                                            <td></td>
                                                            <td>{{ $data->recorder->name }} : {{ $data->comment }} 
                                                                <span style="color: #888;">
                                                                    ({{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }})
                                                                </span>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="5">
                                                                ไม่พบรายการ
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 d-flex flex-column">
                    <div class="user-info-list card flex-grow-1">
                        <div class="card-header card-header-divider d-flex justify-content-between">
                            <span>เกี่ยวกับฉัน</span>
                            
                            @if($dormitoryUser)
                            <a class="icon black-icon" href="{{ route('dormitorys.users.edit', ['id' => $id, 'dormitory_user' => true,'room_id' => $room->id,'user_id' => $user_id]) }}">
                                <i class="mdi mdi-settings"></i>
                            </a>
                            {{-- <a href="" role="button" onclick="dormitorys_edit(); return false;" class="text-dark" style="margin-right: 20px;" title="แก้ไขบัญชีผู้ใช้งาน" >
                                <i class="mdi mdi-settings"></i> 
                            </a> --}}
                            @else
                            {{-- <a class="icon black-icon" href="{{ route('users.edit', $user->name) }}" class="text-dark" style="margin-right: 20px;" title="แก้ไขบัญชีผู้ใช้งาน">
                                <i class="mdi mdi-settings"></i>
                            </a> --}}
                            <a href="" role="button" onclick="edit(); return false;" class="text-dark" style="margin-right: 20px;" title="แก้ไขบัญชีผู้ใช้งาน" >
                                <i class="mdi mdi-settings"></i> 
                            </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <table class="no-border no-strip skills">
                                <tbody class="no-border-x no-border-y">
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-account"></span></td>
                                        <td class="item">ชื่อผู้ใช้</td>
                                        <td> <span style="margin-left: -43%;"> : {{ $user->username }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-"></span></td>
                                        <td class="item">รหัสบัตรประชาชน</td>
                                        <td> 
                                            <span style="margin-left: -25%;">
                                                : {{ $user->National_id ?? 'ไม่ประสงค์ใส่รหัสบัตรประชาชน' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-account"></span></td>
                                        <td class="item">ชื่อ</td>
                                        <td> <span style="margin-left: -50%;"> : {{ $user->name }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-"></span></td>
                                        <td class="item">นามสกุล</td>
                                        <td> <span style="margin-left: -40%;"> : {{ $user->last_name }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-"></span></td>
                                        <td class="item">เพศ</td>
                                        <td> <span style="margin-left: -45%;"> 
                                        : {{ $user->sex == 'Not_specified' ? 'ไม่ระบุ' : ($user->sex == 'Male' ? 'ชาย' : 'หญิง') }}
                                        </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-"></span></td>
                                        <td class="item">วันเดือนปีเกิด</td>
                                        <td> <span style="margin-left: -30%;"> : {{ $user->Date_birth }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-"></span></td>
                                        <td class="item">อายุ</td>
                                        <td>
                                            <span style="margin-left: -48%;">
                                                : {{ \Carbon\Carbon::parse($user->Date_birth)->age }} ปี
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-email"></span></td>
                                        <td> อีเมลล์</td>
                                        <td> <span style="margin-left: -40%;"> : {{ $user->email }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-smartphone-android"></span></td>
                                        <td class="item">เบอร์โทร</td>
                                        <td> <span style="margin-left: -38%;"> : {{ $user->phone }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi"></span></td>
                                        <td class="item">ชื่อผู้ติดต่อ</td>
                                        <td> <span style="margin-left: -35%;"> : {{ $user->Emergency_name }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi"></span></td>
                                        <td class="item">ความสัมพันธ์</td>
                                        <td> <span style="margin-left: -30%;"> : {{ $user->relationship }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-smartphone-android"></span></td>
                                        <td class="item">เบอร์โทรติดต่อฉุกเฉิน</td>
                                        <td> <span style="margin-left: -18%;"> : {{ $user->Emergency_phone }} </span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-group"></span></td>
                                        <td class="item">สถานะ</td>
                                        <td> <span style="margin-left: -40%;"> : 
                                            @forelse ($data_laravel as $item)
                                            {{ $item->user_groups->note }}
                                            @empty
                                                ไม่มีสถานะ
                                            @endforelse 
                                        </span></td>
                                    </tr>
                                    @if($dormitoryUser)
                                    <tr>
                                        <td class="icon"><span class="mdi mdi-pin"></span></td>
                                        <td class="item">ค้างชำระ : </td>
                                        <td> 
                                            <div class="dropdown">
                                            <span style="margin-left: -35%;"> 
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownAirRooms" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{$total_unpaid}} บาท <br>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownAirRooms">                                                
                                                @forelse ($data_month as $month)
                                            <a class="dropdown-item" href="{{ route('dormitorys.monthly_rent.index',['id' => $room->id ,'month_id' => $month->id ,'user'=>$user->id] ) }}" style="margin-right: 20px;" title="ดูค่าเช่าที่ค้างจ่าย">
                                                จำนวน : {{$month->monthly_rent}} ({{$month->id}})<br>
                                            </a>
                                                @empty
                                                    <div class="text-center" colspan="5">
                                                        ไม่พบรายการ
                                                    </div>
                                                @endforelse
                                                </div>
                                            </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <a class="btn btn-secondary btn-rounded shadow-sm" style="float:right; margin-right: 20px;" href="{{ $previousUrll }}">
                <i class="mdi mdi-chevron-left"></i> ย้อนกลับ
            </a>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-Comment" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เพิ่มคอมเมนต์ผู้ใช้</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.comment_insert', $id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="comment">คอมเมนต์</label>
                            <input type="text" name="comment" id="comment" class="form-control" value="" placeholder="เขียนคอมเมนต์" required>
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
<div class="modal fade colored-header colored-header-primary" id="modal-edit" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">แก้ไขบัญชีผู้ใช้</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users.update', $user->name) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="username">ชื่อผู้ใช้</label>
                            <input type="text" name="username" id="username" value="{{ $user->username }}" class="form-control" maxlength="50" required>
                        </div>
                        <div class="modal-body">
                            <label for="National_id">รหัสบัตรประชาชน</label>
                            <input type="text" name="National_id" id="National_id" value="{{ $user->National_id }}" maxlength="13" class="form-control" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข">
                        </div>
                        <div class="modal-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="last_name">นามสกุล</label>
                            <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" class="form-control" pattern="[A-Za-z0-9ก-๙\s]+" title="กรุณาใส่เฉพาะตัวอักษรและตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="sex">เพศ</label>
                            <select name="sex" id="sex" required>
                                <option value="Not_specified" {{ $user->sex == 'Not_specified' ? 'selected' : '' }}>ไม่ระบุ</option>
                                <option value="Male" {{ $user->sex == 'Male' ? 'selected' : '' }}>ชาย</option>
                                <option value="female" {{ $user->sex == 'female' ? 'selected' : '' }}>หญิง</option>
                            </select>
                        </div>

                        <div class="modal-body">
                            <label for="Date_birth">วันเดือนปีเกิด</label>
                            <input type="text" name="Date_birth" id="Date_birth" class="form-control datepicker" value="{{ Carbon::parse($user->Date_birth)->format('d-m-Y') }}" required>
                        </div>

                        <div class="modal-body">
                            <label for="email">อีเมล</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control" required>
                        </div>
    
                        <div class="modal-body">
                            <label for="imgpro">รูปประจำตัว</label>
                            @if($user->imgpro)
                                <img src="{{ asset('storage/' . $user->imgpro) }}" id="imgpro-preview" style="width: 50px; height: 50px; border-radius: 50%; display: block; margin-bottom: 10px;">
                            @else
                                <img src="" id="imgpro-preview" style="width: 50px; height: 50px; border-radius: 50%; display: none; margin-bottom: 10px;">
                            @endif
                            <input class="inputfile" id="imgpro" type="file" name="imgpro" accept="image/*" onchange="previewImage(event)">
                            <label class="btn-secondary" id="btnImg" for="imgpro"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span>                        
                        </div>
    
                        <div class="modal-body">
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" name="password" id="password" placeholder="ใส่เพื่อเปลี่ยนรหัส" class="form-control">
                        </div>
    
                        <div class="modal-body">
                            <label for="phone">เบอร์โทร</label>
                            <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข" required>
                        </div>
                        <div class="modal-body">
                            <label for="Emergency">บุคคลที่ติดต่อฉุกเฉิน</label>
                            
                            <div class="form-group row">
                                <label for="Emergency_name" class="col-sm-3 col-form-label">ชื่อผู้ติดต่อ</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Emergency_name" id="Emergency_name" class="form-control" value="{{ $user->Emergency_name }}" placeholder="กรุณาใส่ชื่อผู้ที่เกี่ยวข้อง">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="relationship" class="col-sm-3 col-form-label">ความสัมพันธ์</label>
                                <div class="col-sm-9">
                                    <input type="text" name="relationship" id="relationship" class="form-control" value="{{ $user->relationship }}" placeholder="กรุณาใส่ความสัมพันธ์">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Emergency_phone" class="col-sm-3 col-form-label">เบอร์โทร</label>
                                <div class="col-sm-9">
                                    <input type="text" name="Emergency_phone" id="Emergency_phone" class="form-control" value="{{ $user->Emergency_phone }}" placeholder="กรุณาใส่เบอร์โทร" maxlength="10" pattern="\d*" title="กรุณาใส่เฉพาะตัวเลข">
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <label for="roles">การเลือกสถานะผู้ใช้</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin" name="roles[]" value="1" {{ in_array(1, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_admin">admin-dragon</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_owner" name="roles[]" value="2" {{ in_array(2, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_owner">เจ้าของหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_admin_dorm" name="roles[]" value="3" {{ in_array(3, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_admin_dorm">แอดมินหอพัก</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_tenant" name="roles[]" value="4" {{ in_array(4, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_tenant">ผู้เช่า</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role_general" name="roles[]" value="5" {{ in_array(5, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_general">ผู้ใช้งานทั่วไป</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body text-right">
                            <label></label>
                            <button type="submit" class="btn btn-primary btn-rounded" style="float:right; margin-right: 20px;">
                                <i class="mdi mdi-account-add"></i> อัปเดต
                            </button>
                            <a href="{{ route('users.delete', $user->id) }}" class="btn btn-danger btn-rounded" onclick="return confirm('คุณต้องการลบ {{$user->name}} หรือไม่ ?')" style="float:right; margin-right: 20px;">
                                <i class="mdi mdi-delete"></i> ลบ
                            </a>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
<div class="modal fade colored-header colored-header-primary" id="modal-review_create" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เพิ่มคอมเมนต์ผู้ใช้</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users_review.insert', $id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" maxlength="50" readonly required>
                            <input type="hidden" name="users_id" id="users_id" class="form-control" value="{{ $id }}" required>
                        </div>
                        
                        <div class="modal-body">
                            <label for="dormitorys_name">หอพัก</label>
                            <input type="text" name="dormitorys_name" id="dormitorys_name" class="form-control" value="{{ $dormitory ? $dormitory->name : '' }}" placeholder="กรุณาใส่ชื่อหอพัก" maxlength="50" required>
                            <input type="hidden" name="dormitorys_id" id="dormitorys_id" class="form-control" value="{{ $dormitory ? $dormitory->id : '' }}" required>
                            @error('dormitorys_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="modal-body">
                            <label for="star">ดาว</label>
                            <select name="star" id="star" class="form-control" required>
                                <option value="" disabled selected>กรุณาให้คะแนน</option>
                                <option value=1>1 ดาว</option>
                                <option value=2>2 ดาว</option>
                                <option value=3>3 ดาว</option>
                                <option value=4>4 ดาว</option>
                                <option value=5>5 ดาว</option>
                            </select>
                            @error('star')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
@if ($users_review)
<div class="modal fade colored-header colored-header-primary" id="modal-review_edit" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">แก้ไขคอมเมนต์</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('users_review.update', $users_review->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="name">ชื่อ</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $users_review->user->name }}" maxlength="50" readonly required>
                            <input type="hidden" name="users_id" id="users_id" value="{{ $users_review->users_id }}" class="form-control" required>
                        </div>
    
                        <div class="modal-body">
                            <label for="dormitorys_id">หอพัก</label>
                            <input type="text" name="dormitorys_name" id="dormitorys_name" class="form-control" value="{{ $users_review->dormitory->name }}" placeholder="กรุณาใส่ชื่อหอพัก" maxlength="50" readonly required>
                            <input type="hidden" name="dormitorys_id" id="dormitorys_id" value="{{ $users_review->dormitorys_id }}" class="form-control" required>
                        </div>
                        <div class="modal-body">
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
                        
                        <div class="modal-body text-right">
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
    </div>
</div>
@endif
<div class="modal fade colored-header colored-header-primary" id="modal-changeImg" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">เปลี่ยนรูปภาพประจำตัว</h3>
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
            </div>
            <form action="{{ route('users.change_img_user', $user->id) }}" method="post" id="modal-form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row form-group justify-content-md-center">
                        <!-- ภาพตัวอย่างที่เลือกใหม่ (ซ่อนตอนแรก) -->
                        <img src="" id="imguser-preview" style="width: 50px; height: 50px; border-radius: 50%; display: none; margin-bottom: 10px;">
                        <!-- ภาพโปรไฟล์ที่มีอยู่ (แสดงตอนแรก) -->
                        {{-- <img src="{{ asset('storage/' . $user->imgpro) }}" id="imgpro-preview" style="width: 50px; height: 50px; border-radius: 50%; display: block; margin-bottom: 10px;"> --}}
                    </div>
                    <div class="form-row form-group justify-content-md-center">
                        <input class="inputfile" id="imguser" type="file" name="imguser" accept="image/*" onchange="previewImageuser(event)">
                        <label class="btn-secondary" id="btnImguser" for="imguser">
                            <i class="mdi mdi-upload"></i>
                            <span id="nameImguser">เลือกรูปภาพประจำตัว . . . . .</span>
                        </label>
                    </div>
                    <div class="row float-right" style="margin-top:0px; margin-bottom:20px; margin-right:auto">
                        <button class="btn btn-lg btn-space btn-secondary modal-close" type="reset" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-lg btn-space btn-primary md-close" type="submit">บันทึก</button>
                    </div>
                </div>
            </form>
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
    .card {
        border-radius: 15px;
    }
    .d-flex {
        display: flex;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    .text-right {
        text-align: right;
    }
    .top-right {
        position: relative;
        top: 0;
        right: 0;
    }
    .black-icon {
        color: rgb(100, 100, 100);
    }
    .form-control {
        border-radius: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
</style>
@endsection

@section('js_link')

    <script src="/assets/lib/jquery-flot/jquery.flot.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/jquery.flot.pie.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/jquery.flot.time.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/jquery.flot.resize.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/plugins/jquery.flot.orderBars.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/plugins/curvedLines.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-flot/plugins/jquery.flot.tooltip.js" type="text/javascript"></script>
    <script src="/assets/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/assets/js/app-page-profile.js" type="text/javascript"></script>

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
            alert('กรุณาเลือกคอมเมนต์ที่ต้องการลบ');
            return;
        }
        if (confirm('คุณต้องการลบคอมเมนต์ที่เลือกหรือไม่ ?')) {
            document.getElementById('deleteForm').submit();
        }
    });
    window.addEventListener('load', function() {
        var userDisplay = document.querySelector('.user-display-container');
        var topRight = document.querySelector('.top-right');
        topRight.style.height = userDisplay.offsetHeight + 'px';
    });
        $(document).ready(function(){
    });
    function Comment() {
        $("#modal-Comment").modal("show");
    }
    function edit() {
        $("#modal-edit").modal("show");

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
    function review_create() {
        $("#modal-review_create").modal("show");
    }
    function review_edit() {
        $("#modal-review_edit").modal("show");
    }
    function changeImg() {
        $("#modal-changeImg").modal("show");
    }

    function previewImageuser(event) {
        const reader = new FileReader();
        reader.onload = function() {
        const imgUserPreview = document.getElementById('imguser-preview');
        const imgProPreview = document.getElementById('imgpro-preview');

        // แสดงรูปภาพใหม่ที่ผู้ใช้เลือก
        imgUserPreview.src = reader.result;
        imgUserPreview.style.display = 'block'; // แสดงรูปใหม่
        imgProPreview.style.display = 'none'; // ซ่อนรูปเดิม
            };
        reader.readAsDataURL(event.target.files[0]);

        // อัปเดตปุ่มและข้อความเมื่อมีการเลือกรูปภาพ
        document.getElementById("nameImguser").textContent = 'เลือกรูปภาพสำเร็จ';
        document.getElementById("btnImguser").classList.add('btn-success');
    }

    function previewImage_dormitory(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imgpro_dormitory-preview');
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
