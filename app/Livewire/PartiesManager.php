<?php

namespace App\Livewire;

use App\Models\Institution;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\PermissionRegistrar;

#[Layout('components.layouts.app')]
class PartiesManager extends Component
{
    use WithPagination;

    public Institution $institution;

    public string $search = '';

    public int $perPage = 10;

    public ?int $editingId = null;

    public string $name = '';

    public string $type = 'internal'; // internal | external

    public bool $is_active = true;

    public bool $showModal = false;

    public function mount(Institution $institution): void
    {
        $this->institution = $institution;
        $registrar = app(PermissionRegistrar::class);

        // Check global superadmin (no team)
        $registrar->setPermissionsTeamId(null);
        $isSuper = Auth::user()->hasRole('superadmin');

        // Check institution admin using both team-context and direct pivot check
        $teamId = $this->institution->id;
        $registrar->setPermissionsTeamId($teamId);
        $hasTeamRole = Auth::user()->hasRole('admin');
        $hasPivotRole = Auth::user()->roles()
            ->where('name', 'admin')
            ->wherePivot(config('permission.column_names.team_foreign_key'), $teamId)
            ->exists();
        $isAdmin = $hasTeamRole || $hasPivotRole;

        abort_unless($isSuper || $isAdmin, 403);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['internal', 'external'])],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(?int $id = null): void
    {
        $this->resetForm();
        if ($id) {
            $p = Party::where('institution_id', $this->institution->id)->findOrFail($id);
            $this->editingId = $p->id;
            $this->name = $p->name;
            $this->type = $p->type;
            $this->is_active = (bool) $p->is_active;
        }
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'institution_id' => $this->institution->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Party::where('institution_id', $this->institution->id)
                ->findOrFail($this->editingId)
                ->update($data);
        } else {
            Party::create($data);
        }

        $this->dispatch('party-saved');
        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $p = Party::where('institution_id', $this->institution->id)->findOrFail($id);
        $p->update(['is_active' => ! (bool) $p->is_active]);
        $this->dispatch('party-saved');
    }

    public function delete(int $id): void
    {
        Party::where('institution_id', $this->institution->id)->findOrFail($id)->delete();
        $this->dispatch('party-deleted');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->type = 'internal';
        $this->is_active = true;
    }

    public function render()
    {
        $query = Party::query()
            ->where('institution_id', $this->institution->id)
            ->when($this->search !== '', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('type')
            ->orderBy('name');

        return view('livewire.parties-manager', [
            'parties' => $query->paginate($this->perPage),
        ]);
    }
}
