@extends('core::layouts.master')
@php
    $page_id='account';
    $page_name='Account';
    $page_title='Account';
@endphp
@section('content')
<div class="user-profile">
    <div class="row">
        <div class="col-lg-6">
            <div class="user-display">
                <div class="user-display-bg"><img src="/assets/img/user-profile-display.png" alt="Profile Background"></div>
                <div class="user-display-bottom">
                    <div class="user-display-avatar">
                        <a href="" role="button" onclick="changeImg(); return false;">
                            <img id="sh_img" src="{{ auth()->user()->imgpro ? asset('storage/' . auth()->user()->imgpro) : '/assets/img/avatar-150.png' }}" onerror="this.onerror=null; this.src='/assets/img/avatar-150.png'" alt="Avatar">
                        </a>
                    </div>
                    <div class="user-display-info">
                        <div class="name">{{auth()->user()->name}}</div>
                        <div class="nick"><span class="mdi mdi-email">
                            </span> {{auth()->user()->email}}</div>
                    </div>
                    <div class="row user-display-details">
                        <div class="col-4">
                            <div class="title">ชื่อผู้ใช้</div>
                            <div class="counter">{{ $user->username }}</div>
                        </div>
                        <div class="col-4">
                            <div class="title">หอพัก</div>
                            <div class="counter">{{$dormitory->name}}</div>
                        </div>
                        <div class="col-4">
                            <div class="title">ห้อง</div>
                            <div class="counter">{{ $room->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-info-list card">
                <div class="card-header card-header-divider">เกี่ยวกับฉัน
                    <div class="tools">
                    </div>
                </div>
                <div class="card-body">
                  <table class="no-border no-strip skills">
                    <tbody class="no-border-x no-border-y">
                        <tr>
                            <td class="icon"><span class="mdi mdi-account"></span></td>
                            <td class="item">ชื่อผู้ใช้</td>
                            <td> <span style="margin-left: -40%;"> : {{ $user->username }} </span></td>
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
                        {{-- <tr>
                            <td class="icon"><span class="mdi mdi-group"></span></td>
                            <td class="item">สถานะ</td>
                            <td> <span style="margin-left: -40%;"> : {{ $user->user_group->note }} </span></td>
                        </tr> --}}
                        <tr>
                            <td class="icon"><span class="mdi mdi-pin"></span></td>
                            <td class="item">Website</td>
                            <td> <span style="margin-left: -35%;"> : www.website.com </span></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 ">
                <div class="card">
                  @if($data_user)
                    @php
                        $sum = \Modules\User\Entities\Modules_User_review::where('status_delete', '!=', 'Disable')->where('users_id', $id)->sum('star');
                        $count = \Modules\User\Entities\Modules_User_review::where('status_delete', '!=', 'Disable')->where('users_id', $id)->count();
                        $average = $count > 0 ? $sum / $count : 0;
                        $userReview = \Modules\User\Entities\Modules_User_review::where('users_id', $id)
                            ->where('dormitorys_id', $dormitory->id)
                            ->where('status_delete', '!=', 'Disable')
                            ->first();
                    @endphp
                    @endif
                    <div class="card-header card-header-divider">รีวิวจากหอพัก
                      @if($data_user)
                        <span class="ml-auto">ค่าเฉลี่ย: {{$average}} <i class="mdi mdi-star"></i></span>
                      @endif
                        <div>
                        </div>
                    </div>
                    <div class="card-body">
                            @forelse ($review as $item)
                                <div class="col-10">{{ $item->dormitory->name }} :  {{ $item->star }} <i class="mdi mdi-star"></i> </div>
                                <div></div>
                            @empty
                                <div class="text-center">
                                    ไม่พบรายการ
                                </div>
                            @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header card-header-divider">คอมเมนต์
                        <div class="tools">
                            {{-- <a href="{{ route('users_Comment.create', $id) }}" class="btn btn-rounded" title="เพิ่มคอมเมนต์">
                                <i class="mdi mdi-plus"></i>
                            </a> --}}
                            <a href="" role="button" onclick="Comment(); return false;" class="btn btn-rounded" title="เพิ่มคอมเมนต์">
                                <i class="mdi mdi-plus"></i> 
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse ($comments as $data)
                            <div class="col-10">
                                <span class="title">{{ $data->recorder->name }} : {{ $data->comment }} <span style="color: #888;">({{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }})</span></span>
                            </div>
                            <div></div>
                        @empty
                            <div class="text-center">
                                ไม่พบรายการ
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade colored-header colored-header-primary" id="modal-changeImg" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">เปลี่ยนรูปภาพประจำตัว</h3>
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
                </div>
                <form action="{{ route('change_img_pro') }}" method="post" id="modal-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row form-group justify-content-md-center">
                            <img id="blah" src="{{ auth()->user()->imgpro ? asset('storage/' . auth()->user()->imgpro) : '/assets/img/avatar-150.png' }}" alt="Avatar" style="width:150px; height:150px;" class="rounded-circle mx-auto d-block img-thumbnail">
                        </div>
                        <div class="form-row form-group justify-content-md-center">
                            <input class="inputfile" id="imgInp" type="file" name="image" accept="image/*" required>
                            <label class="btn-secondary" id="btnImg" for="imgInp"><i class="mdi mdi-upload"></i><span id="nameImg">เลือกรูปภาพประจำตัว . . . . .</span></label>
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
@endsection

@section('css')
<style></style>
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
@endsection

@section('js_script')
<script type="text/javascript">
    $(document).ready(function(){
        // Additional JavaScript code
    });

    function changeImg() {
        $('#blah').attr('src', document.getElementById("sh_img").getAttribute("src"));
        $("#nameImg").text('เลือกรูปภาพประจำตัว . . . . .');
        $("#btnImg").removeClass('btn-success');
        $("#modal-changeImg").modal("show");
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
        $("#nameImg").text('เลือกรูปภาพสำเร็จ');
        $("#btnImg").addClass('btn-success');
    });
    function Comment() {
        $("#modal-Comment").modal("show");
    }
    
</script>
@endsection