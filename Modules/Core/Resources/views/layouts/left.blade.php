<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a class="left-sidebar-toggle" href="">{{$page_name}}</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">

                    <ul class="sidebar-elements">
                        <li class="divider">เมนู</li>

                        {{-- main menu --}}
                        <li class="{{$page_id=="home_core"? 'active': ''}}"><a href="/home"><i class="icon mdi mdi-home"></i><span>หน้าหลัก</span></a>
                        </li>

                        @if(auth()->user()->user_type == 1)
                            <li class="parent"><a href=""><i class="icon mdi mdi-accounts"></i><span>รายชื่อผู้ใช้งาน</span></a>
                                <ul class="sub-menu">
                                    <li class="{{$page_id=="users"? 'active': ''}}"><a href="/users"><span>ผู้ใช้งาน</span></a>
                                    </li>
                                    
                                    <li class="{{$page_id=="users_group"? 'active': ''}}"><a href="/usergroup"><span>กลุ่มผู้ใช้งาน</span></a>
                                    </li>
                                    
                                </ul>
                            </li>
                        @endif


                        <li class="{{$page_id=="users"? 'active': ''}}"><a href="/users"><i class="icon mdi mdi-accounts"></i><span>ผู้ใช้งาน</span></a>
                        </li>
                        <li class="{{$page_id=="users_group"? 'active': ''}}"><a href="/usergroup"><i class="icon mdi mdi-accounts"></i><span>กลุ่มผู้ใช้งาน</span></a>
                        </li>

                        @if (Route::has('dormitorys.index'))

                            @if(isset($dormitory_name))
                            <li class="parent"><a style="cursor: pointer;" onclick="window.location.href = '{{route('dormitorys.index')}}'"><i class="icon mdi mdi-city-alt"></i><span>รายชื่อหอพัก</span></a>
                                <ul class="sub-menu">
                                    <li class="{{$page_id=="dormitory_dt"? 'active': ''}}"><a href="{{route('dormitorys.show',$dormitory_code)}}"><i class="icon mdi mdi-arrow-right"></i><span> {{$dormitory_name}}</span></a>
                                    </li>
                                    {{-- @if(in_array(auth()->user()->user_type, [1, 2, 3 ])) --}}
                                        <li class="{{$page_id=="dormitory_users"? 'active': ''}}"><a href="{{route('dormitorys.users.index',$dormitory_code)}}"><span>ผู้ใช้งาน</span></a>
                                        </li>
                                        <li class="{{$page_id=="dormitory_rooms"? 'active': ''}}"><a href="{{route('dormitorys.rooms.index',$dormitory_code)}}"><span>ห้องพัก</span></a>
                                        </li>
                                        {{-- <li class="{{$page_id=="dormitory_check_in"? 'active': ''}}"><a href="{{route('dormitorys.check_in.index',$dormitory_code)}}"><span>เช็คอินเข้า</span></a>
                                        </li>
                                        <li class="{{$page_id=="dormitory_check_out"? 'active': ''}}"><a href="{{route('dormitorys.check_out.index',$dormitory_code)}}"><span>เช็คอินออก</span></a>
                                        </li> --}}
                                        <li class="{{$page_id=="dormitory_meters"? 'active': ''}}"><a href="{{route('dormitorys.meters.index',['code' => $dormitory->code, 'filter' => 'electric'])}}"><span>จดมิเตอร์</span></a>
                                        </li>
                                        <li class="{{$page_id=="dormitory_billings"? 'active': ''}}"><a href="{{route('dormitorys.billings.index',$dormitory_code)}}"><span>บิล/การเงิน</span></a>
                                        </li>
                                        <li class="{{$page_id=="dormitory_lease_agreements"? 'active': ''}}"><a href="{{route('dormitorys.lease_agreements.index',$dormitory_code)}}"><span>สัญญาเช่า</span></a>
                                        </li>
                                        <li class="{{$page_id=="dormitory_reports"? 'active': ''}}"><a href="{{route('dormitorys.reports.index',$dormitory_code)}}"><span>สรุปรายงาน</span></a>
                                        </li>
                                    {{-- @endif --}}
                                    {{-- @if(auth()->user()->user_type ==  4 ) --}}
                                        {{-- <li class="{{$page_id=="dormitory_rooms"? 'active': ''}}"><a href="{{route('dormitorys.rooms.index',$dormitory_code)}}"><span>ห้องพัก</span></a>
                                        </li> --}}
                                    {{-- @endif --}}
                                </ul>
                            </li>
                            @else
                            <li class="{{$page_id=="dormitorys"? 'active': ''}}"><a href="{{route('dormitorys.index')}}"><i class="icon mdi mdi-city"></i><span>รายชื่อหอพัก</span></a>
                            </li>
                            @endif

                        @endif
                        {{-- @if(auth()->user()->user_type != 5)
                            <li class="{{$page_id=="controlsystem"? 'active': ''}}"><a href="/controlsystem"><i class="icon mdi mdi-wrench"></i><span>ระบบควบคุม</span></a>
                            </li>
                        @endif --}}

                        {{-- sub menu level --}}
                        {{-- <li class="parent"><a href=""><i class="icon mdi mdi-flickr"></i><span>Sub_Menu</span></a>
                            <ul class="sub-menu">x
                                <li class="{{$page_id=="sub_menu"? 'active': ''}}"><a href="">sub_menu_1</a>
                                </li>
                            </ul>
                        </li> --}}


                        <li class="divider">Quick Menu</li>
                        <li class="{{$page_id=="account"? 'active': ''}}"><a href="/account"><i class="icon mdi mdi-account"></i><span>บัญชีผู้ใช้</span></a>
                        </li>
                        <li class=""><a  href="" data-target="#cf_logout" data-toggle="modal"><i class="icon mdi mdi-power"></i><span>ออกจากระบบ</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- <div class="progress-widget">
            <div class="progress-data"><span class="progress-value">60%</span><span class="name">Current
                    Project</span></div>
            <div class="progress">
                <div class="progress-bar progress-bar-primary" style="width: 60%;"></div>
            </div>
        </div> --}}
    </div>
</div>
