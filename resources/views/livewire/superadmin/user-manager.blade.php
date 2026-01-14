<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        <h2 class="h4 mb-0 me-3">
            <i class="fa-solid fa-users-cog me-2"></i>إدارة المستخدمين
        </h2>
        <div class="ms-auto d-flex gap-2">
            <select class="form-select" style="max-width: 280px;" wire:model.live="selectedInstitutionId">
                <option value="">حسابات المشرفين العامين</option>
                @foreach($institutions as $inst)
                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" wire:click="openCreateModal" title="إضافة مستخدم ">
                <i class="fa-solid fa-user-plus me-1"></i>
                إضافة مستخدم
            </button>
            <input type="text" class="form-control" placeholder="بحث بالاسم/البريد" wire:model.live="search"
                style="max-width: 280px;">
        </div>
    </div>
    <!-- Create User Modal -->
    <div class="modal fade @if($showCreateModal) show @endif" @if($showCreateModal) style="display:block;" @endif
        tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if(is_null($selectedInstitutionId))
                        إضافة مشرف عام
                        @else
                        إضافة مستخدم للمؤسسة المختارة
                        @endif
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeCreateModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="createUser">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم</label>
                                <input type="text" class="form-control" wire:model.live="name">
                                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" wire:model.live="email">
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">كلمة المرور</label>
                                <input type="password" class="form-control" wire:model.live="password">
                                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeCreateModal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($showCreateModal)
    <div class="modal-backdrop fade show"></div>
    @endif

    <div class="card">
        <div class="card-header">المستخدمون</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>المؤسسة</th>
                        @if(!is_null($selectedInstitutionId))
                        <th>مدير</th>
                        <th>صلاحيات المستخدم</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ optional($u->institution)->name }}</td>
                        @if(!is_null($selectedInstitutionId))
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is-admin-{{ $u->id }}"
                                    @checked($u->hasRole('admin'))
                                wire:change="updateAdminRole({{ $u->id }}, $event.target.checked)">
                                <label class="form-check-label" for="is-admin-{{ $u->id }}">مدير مؤسسة</label>
                            </div>
                        </td>
                        <td>
                            @if(!$u->hasRole('admin'))
                            <div class="row g-2">
                                @foreach($types as $tKey => $tLabel)
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="fw-bold mb-2">{{ $tLabel }}</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @php($actions = ['view' => 'عرض','create' => 'إنشاء','update' =>
                                            'تعديل','delete' => 'حذف'])
                                            @foreach($actions as $actKey => $actLabel)
                                            @php($perm = 'letters.' . $tKey . '.' . $actKey)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="perm-{{ $u->id }}-{{ $tKey }}-{{ $actKey }}"
                                                    @checked($u->getPermissionNames()->contains($perm))
                                                wire:change="updateUserPermission({{ $u->id }}, '{{ $tKey }}',
                                                '{{ $actKey }}', $event.target.checked)">
                                                <label class="form-check-label"
                                                    for="perm-{{ $u->id }}-{{ $tKey }}-{{ $actKey }}">{{ $actLabel }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-muted">المدير يمتلك كافة الصلاحيات ضمن المؤسسة.</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $users->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('user-created', () => {
        Swal.fire({
            icon: 'success',
            title: 'تم إنشاء المستخدم',
            timer: 2000,
            showConfirmButton: false
        });
    });
    Livewire.on('user-updated', () => {
        Swal.fire({
            icon: 'success',
            title: 'تم تحديث المستخدم',
            timer: 2000,
            showConfirmButton: false
        });
    });
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('user-created', () => {
        Swal.fire({
            icon: 'success',
            title: 'تم إنشاء المستخدم',
            text: 'تم إنشاء المستخدم وتعيين الصلاحيات بنجاح',
            timer: 2000,
            showConfirmButton: false
        });
    });
});
</script>
@endpush