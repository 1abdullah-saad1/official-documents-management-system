<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        <h2 class="h4 mb-0 me-3">
            <i class="fa-solid fa-building-columns me-2"></i>إدارة المؤسسات
        </h2>
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-primary" wire:click="openModal">
                <i class="fa-solid fa-plus me-1"></i>
                إضافة مؤسسة
            </button>
            <input type="text" class="form-control" placeholder="بحث بالاسم" wire:model.live="search" style="max-width: 280px;">
        </div>
    </div>

    <div class="card">
        <div class="card-header">المؤسسات</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th class="text-end">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($institutions as $inst)
                    <tr>
                        <td>{{ $inst->id }}</td>
                        <td>{{ $inst->name }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $inst->id }})">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $inst->id }})" onclick="return confirm('حذف هذه المؤسسة؟')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">لا توجد بيانات</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $institutions->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade @if($showModal) show @endif" @if($showModal) style="display: block;" @endif tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@if($editingId) تعديل مؤسسة @else إضافة مؤسسة @endif</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $editingId ? 'update' : 'create' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المؤسسة</label>
                            <input type="text" class="form-control" wire:model="name" autofocus>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($showModal)
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('institution-created', () => {
            Swal.fire({
                icon: 'success',
                title: 'تم الإضافة',
                text: 'تم إضافة المؤسسة بنجاح',
                timer: 2000,
                showConfirmButton: false
            });
        });

        Livewire.on('institution-updated', () => {
            Swal.fire({
                icon: 'success',
                title: 'تم التحديث',
                text: 'تم تحديث المؤسسة بنجاح',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });
</script>
@endpush
