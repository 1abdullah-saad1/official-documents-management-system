<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header my-0" style="padding-top: 7px;padding-bottom:7px;">
        <img src="{{ asset('assets/images/legal-base-logo.png') }}" alt="logo" width="37.5" height="37.5"
            class="mx-2 rounded-circle">
        <div style="font-size: 18px;font-weight: bold;">{{ __('app.project_name') }}</div>
        <button type="button" class="btn-close py-0 my-0" style="font-size: 14px;font-weight: bold;"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body alert alert-secondary rounded-0 p-0 m-0">
        <ul class="list-group list-group-flush my-1">
            <a class="navbar-brand" href="{{ url('/') }}">
                <li class="list-group-item mx-2 my-1 rounded-2"><i
                        class="fas fa-home mx-2"></i>{{ __('الصفحة الرئيسية') }}</li>
            </a>
            @role('superadmin')
            <a class="navbar-brand" href="{{ route('superadmin.institutions') }}">
                <li class="list-group-item mx-2 my-1 rounded-2"><i
                        class="fas fa-sitemap mx-2"></i>{{ __('إدارة المؤسسات') }}
                </li>
            </a>
            <a class="navbar-brand" href="{{ route('superadmin.users') }}">
                <li class="list-group-item mx-2 my-1 rounded-2"><i
                        class="fas fa-envelope mx-2"></i>{{ __('إدارة المستخدمين') }}
                </li>
            </a>

            @endrole
        </ul>
    </div>
</div>
