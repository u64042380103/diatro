<!DOCTYPE html>
<html lang="en">
@include('core::layouts.head')
<body>
    <div class="be-wrapper">
        @include('core::layouts.top')
        @include('core::layouts.left')
        <div class="be-content @yield('body_class')">
            <div class="main-content container-fluid">
                @yield('content')
            </div>
        </div>
        {{-- @include('core::layouts.right') --}}
    </div>
    @include('core::layouts.script')
</body>
</html>
