<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header my-0" style="padding-top: 7px;padding-bottom:7px;">
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="37.5" height="37.5"
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

            @auth
            @php
            $teamId = auth()->user()->institution_id;
            $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
            $registrar->setPermissionsTeamId($teamId);
            $hasTeamRole = $teamId ? auth()->user()->hasRole('admin') : false;
            $hasPivotRole = $teamId ? auth()->user()->roles()
            ->where('name', 'admin')
            ->wherePivot(config('permission.column_names.team_foreign_key'), $teamId)
            ->exists() : false;
            $isInstitutionAdmin = $teamId && ($hasTeamRole || $hasPivotRole);
            @endphp
            @if($isInstitutionAdmin)
            <a class="navbar-brand" href="{{ route('institutions.parties', $teamId) }}">
                <li class="list-group-item mx-2 my-1 rounded-2"><i class="fas fa-people-group mx-2"></i>
                    {{ __('جهات المخاطبة') }}
                </li>
            </a>
            @endif
            @endauth
        </ul>
    </div>
</div>
