<nav class="navbar navbar-expand fixed-top be-top-header">
    <div class="container-fluid">
        <div class="be-navbar-header"><a class="navbar-brand" href="/"></a>
        </div>
        <div class="page-title"><span>{{$page_name}}</span></div>
        <div class="be-right-navbar">
            <ul class="nav navbar-nav float-right be-user-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">
                        <img src="{{ auth()->user()->imgpro ? asset('storage/' . auth()->user()->imgpro) : '/assets/img/avatar-150.png' }}" onerror="this.onerror=null; this.src='/assets/img/avatar.png'" alt="Avatar" class="user-avatar">
                        <span class="user-name">{{auth()->user()->name}}</span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <div class="user-info">
                            <div class="user-name">{{auth()->user()->name}}</div>
                            <div class="user-position online">Available</div>
                        </div>
                        <a class="dropdown-item" href="/account"><span
                            class="icon mdi mdi-face"></span>Account</a>
                        <a class="dropdown-item" href="" data-target="#cf_logout" data-toggle="modal"><span
                            class="icon mdi mdi-power"></span>Logout</a>
                    </div>
                </li>
            </ul>
            <ul class="nav navbar-nav float-right be-icons-nav">
                {{-- <li class="nav-item dropdown"><a class="nav-link be-toggle-right-sidebar" href="#" role="button"
                        aria-expanded="false"><span class="icon mdi mdi-settings"></span></a></li> --}}
                {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                        role="button" aria-expanded="false"><span class="icon mdi mdi-notifications"></span><span
                            class="indicator"></span></a>
                    <ul class="dropdown-menu be-notifications">
                        <li>
                            <div class="title">Notifications<span class="badge badge-pill">3</span></div>
                            <div class="list">
                                <div class="be-scroller-notifications">
                                    <div class="content">
                                        <ul>
                                            <li class="notification notification-unread"><a href="#">
                                                    <div class="image"><img src="/assets/img/avatar2.png"
                                                            alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">Jessica
                                                                Caruso</span> accepted your invitation to join the team.
                                                        </div><span class="date">2 min ago</span>
                                                    </div>
                                                </a></li>
                                            <li class="notification"><a href="#">
                                                    <div class="image"><img src="/assets/img/avatar3.png"
                                                            alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">Joel King</span> is
                                                            now following you</div><span class="date">2 days
                                                            ago</span>
                                                    </div>
                                                </a></li>
                                            <li class="notification"><a href="#">
                                                    <div class="image"><img src="/assets/img/avatar4.png"
                                                            alt="Avatar"></div>
                                                    <div class="notification-info">
                                                        <div class="text"><span class="user-name">John Doe</span> is
                                                            watching your main repository</div><span class="date">2
                                                            days ago</span>
                                                    </div>
                                                </a></li>
                                            <li class="notification"><a href="#">
                                                    <div class="image"><img src="/assets/img/avatar5.png"
                                                            alt="Avatar"></div>
                                                    <div class="notification-info"><span class="text"><span
                                                                class="user-name">Emily Carter</span> is now following
                                                            you</span><span class="date">5 days ago</span></div>
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="footer"> <a href="#">View all notifications</a></div>
                        </li>
                    </ul>
                </li> --}}
                {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                        role="button" aria-expanded="false"><span class="icon mdi mdi-apps"></span></a>
                    <ul class="dropdown-menu be-connections">
                        <li>
                            <div class="list">
                                <div class="content">
                                    <div class="row">
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/github.png"
                                                    alt="Github"><span>GitHub</span></a></div>
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/bitbucket.png"
                                                    alt="Bitbucket"><span>Bitbucket</span></a></div>
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/slack.png" alt="Slack"><span>Slack</span></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/dribbble.png"
                                                    alt="Dribbble"><span>Dribbble</span></a></div>
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/mail_chimp.png" alt="Mail Chimp"><span>Mail
                                                    Chimp</span></a></div>
                                        <div class="col"><a class="connection-item" href="#"><img
                                                    src="/assets/img/dropbox.png"
                                                    alt="Dropbox"><span>Dropbox</span></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer"> <a href="#">More</a></div>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
<div class="modal fade" id="cf_logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span></button>
        </div>
        <div class="modal-body text-center">
            <div style="font-size: 22px;">Are you sure you want to log out ?</div>
            <div class="mt-7 mb-2">
                <button class="btn btn-lg mr-1" style="background-color: rgb(235, 235, 235); color:black;" type="button" data-dismiss="modal" aria-hidden="true"><span class="mdi mdi-close"></span> No, thank</button>
                <button class="btn btn-lg btn-danger ml-1" type="button" onclick="document.getElementById('logout-form').submit();" ><span class="mdi mdi-check-all"></span> Yes, logout</button>
            </div>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
</div>
@section('css')
<style>
    .user-avatar {
    width: 40px; /* Set to desired width */
    height: 40px; /* Set to desired height */
    border-radius: 50%; /* Makes the image circular */
    object-fit: cover; /* Ensures the image covers the area */
}

</style>
@endsection