<?php

namespace App\Livewire\Superadmin;

use App\Models\Institution;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.livewire.superadmin')]
class InstitutionsManager extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public ?int $editingId = null;

    public string $name = '';

    public bool $showModal = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('institutions', 'name')->ignore($this->editingId)],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        Institution::create([
            'name' => $this->name,
        ]);
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('institution-created');
    }

    public function edit(int $id): void
    {
        $inst = Institution::findOrFail($id);
        $this->editingId = $inst->id;
        $this->name = $inst->name;
        $this->showModal = true;
    }

    public function update(): void
    {
        if (! $this->editingId) {
            return;
        }
        $this->validate();
        $inst = Institution::findOrFail($this->editingId);
        $inst->update(['name' => $this->name]);
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('institution-updated');
    }

    public function delete(int $id): void
    {
        Institution::findOrFail($id)->delete();
        $this->dispatch('institution-deleted');
    }

    #[On('reset-form')]
    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
    }

    public function render()
    {
        $query = Institution::query()
            ->when($this->search !== '', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name');

        $institutions = $query->paginate($this->perPage);

        return view('livewire.superadmin.institutions-manager', [
            'institutions' => $institutions,
        ]);
    }
}
