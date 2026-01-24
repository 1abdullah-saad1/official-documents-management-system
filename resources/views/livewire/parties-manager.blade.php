<div class="container py-4">
  <div class="d-flex align-items-center mb-3">
    <h2 class="h5 mb-0 me-3">
      <i class="fa-solid fa-people-group me-2"></i>
      جهات المؤسسة: {{ $this->institution->name }}
    </h2>
    <div class="ms-auto d-flex gap-2">
      <input type="text" class="form-control" style="max-width: 260px" placeholder="بحث بالاسم" wire:model.live="search">
      <button class="btn btn-primary" wire:click="openModal()">
        <i class="fa-solid fa-plus me-1"></i> إضافة جهة
      </button>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>النوع</th>
            <th>الحالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @foreach($parties as $p)
          <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->name }}</td>
            <td>
              <span class="badge bg-secondary">{{ $p->type === 'internal' ? 'داخلية' : 'خارجية' }}</span>
            </td>
            <td>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="active-{{ $p->id }}" @checked($p->is_active)
                       wire:change="toggleActive({{ $p->id }})">
                <label class="form-check-label" for="active-{{ $p->id }}">{{ $p->is_active ? 'مفعلة' : 'موقوفة' }}</label>
              </div>
            </td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary" wire:click="openModal({{ $p->id }})">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $p->id }})">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $parties->links() }}</div>
  </div>

  <!-- Modal -->
  <div class="modal fade @if($showModal) show @endif" @if($showModal) style="display:block;" @endif tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $editingId ? 'تعديل جهة' : 'إضافة جهة' }}</h5>
          <button type="button" class="btn-close" wire:click="closeModal"></button>
        </div>
        <form wire:submit.prevent="save">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">الاسم</label>
                <input type="text" class="form-control" wire:model.live="name">
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">النوع</label>
                <select class="form-select" wire:model.live="type">
                  <option value="internal">داخلية</option>
                  <option value="external">خارجية</option>
                </select>
                @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="active" wire:model.live="is_active">
                  <label class="form-check-label" for="active">مفعلة</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="closeModal">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ</button>
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
  Livewire.on('party-saved', () => {
    Swal.fire({ icon: 'success', title: 'تم الحفظ', timer: 1500, showConfirmButton: false });
  });
  Livewire.on('party-deleted', () => {
    Swal.fire({ icon: 'success', title: 'تم الحذف', timer: 1500, showConfirmButton: false });
  });
});
</script>
@endpush
