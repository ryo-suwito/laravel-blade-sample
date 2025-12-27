<!-- Main navbar -->
<div class="navbar navbar-expand-lg navbar-dark navbar-static">
    <div class="d-flex flex-1 d-lg-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-paragraph-justify3"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-transmission"></i>
        </button>
    </div>

    <div class="navbar-brand text-center text-lg-left">
        <a href="{{ route("cms.dashboard") }}" class="d-inline-block">
            <img src="{{ asset("assets/images/logo_with_background.png") }}" class="d-none d-sm-block" alt="Logo">
            <img src="{{ asset("assets/images/logo_with_background.png") }}" class="d-sm-none" alt="Logo">
        </a>
    </div>

    <div class="collapse navbar-collapse order-2 order-lg-1" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
            </li>
        </ul>

        <ul class="navbar-nav ml-lg-auto">
        </ul>
    </div>

    <ul class="navbar-nav flex-row order-1 order-lg-2 flex-1 flex-lg-0 justify-content-end align-items-center">
        @if (\App\Helpers\S::getTargetType() == "MERCHANT_BRANCH")
            @if(\Illuminate\Support\Facades\Request::path() == 'dashboard')
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="modal" data-target="#modal-on-boarding" class="navbar-nav-link">
                        <img style="width: 15px" src="/assets/images/ic_laptop.png">
                        <span class="d-lg-none ml-3"></span>
                    </a>
                </li>
            @endif
        @endif

        @if (\App\Helpers\S::getTargetType() == "MERCHANT_BRANCH")
            <li class="nav-item dropdown">
                <a href="{{ env("MERCHANT_BRANCH_GUIDE_BOOK_URL", "#") }}" class="navbar-nav-link" target="_blank">
                    <i class="icon-book"></i>
                    <span class="d-lg-none ml-3">@lang("cms.Guide Book")</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\S::getUserRoleList() && count(\App\Helpers\S::getUserRoleList()) > 1)
            <li class="nav-item">
                <form action="{{ route("cms.change_user_role") }}" method="post" id="form-change-user-role">
                    @csrf
                    <select id="select-user-role" name="user_role_id">
                        @foreach (\App\Helpers\S::getUserRoleList() as $user_role)
                            <option value="{{ $user_role->id }}" @if (\App\Helpers\S::getUserRole()->id == $user_role->id) selected @endif>{{ $user_role->target_name }} - {{ $user_role->role->name }}</option>
                        @endforeach
                    </select>
                </form>
            </li>
        @endif

        <li class="nav-item nav-item-dropdown-lg dropdown dropdown-user h-100">
            <a href="#" class="navbar-nav-link navbar-nav-link-toggler dropdown-toggle d-inline-flex align-items-center h-100" data-toggle="dropdown" aria-expanded="false">
                <span class="d-lg-inline-block">@lang("cms.Account")</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route("cms.user.profile") }}" class="dropdown-item"><i class="icon-user"></i>Profile</a>
                {{--                <a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>--}}
                {{--                <a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-primary badge-pill ml-auto">58</span></a>--}}
                {{--                <div class="dropdown-divider"></div>--}}
                {{--                <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>--}}
                <form action="{{ route("cms.logout") }}" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="icon-switch2"></i>Logout</button>
                </form>
            </div>
        </li>

    </ul>
</div>
<!-- /main navbar -->
