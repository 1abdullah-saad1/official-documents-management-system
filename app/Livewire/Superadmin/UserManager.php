<?php

namespace App\Livewire\Superadmin;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layouts.livewire.superadmin')]
class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 10;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public ?int $selectedInstitutionId = null;

    public string $role = ''; // used only in manage table context

    public bool $showCreateModal = false;

    public array $specialties = [
        'external' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
        'internal' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
        'memo' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
        'personal_request' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
        'outgoing' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
    ];

    protected function rules(): array
    {
        $emailRule = ['required', 'email', Rule::unique('users', 'email')];
        // When null, we are creating a global superadmin; otherwise must exist
        $institutionRule = ['nullable', 'integer', Rule::exists('institutions', 'id')];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'password' => ['required', 'string', 'min:8'],
            'selectedInstitutionId' => $institutionRule,
            // role selection is managed inline in table, not in create modal
        ];
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function createUser(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'institution_id' => $this->selectedInstitutionId,
        ]);

        // If no institution selected, make this a global superadmin
        if (is_null($this->selectedInstitutionId)) {
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
            $user->assignRole('superadmin');
        }

        // Otherwise: No role/permission assignment in create modal. Control via table.

        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('user-created');
    }

    protected function collectSelectedPermissions(): array
    {
        $list = [];
        foreach ($this->specialties as $type => $actions) {
            foreach ($actions as $action => $enabled) {
                if ($enabled) {
                    $list[] = "letters.$type.$action";
                }
            }
        }

        return $list;
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->specialties = [
            'external' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'internal' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'memo' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'personal_request' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'outgoing' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
        ];
    }

    public function updateAdminRole(int $userId, bool $isAdmin): void
    {
        if (! $this->selectedInstitutionId) {
            return;
        }
        app(PermissionRegistrar::class)->setPermissionsTeamId($this->selectedInstitutionId);
        $user = User::findOrFail($userId);
        if ($isAdmin) {
            $user->syncRoles(['admin']);
        } else {
            $user->syncRoles(['user']);
        }
        $this->dispatch('user-updated');
    }

    public function updateUserPermission(int $userId, string $type, string $action, bool $checked): void
    {
        if (! $this->selectedInstitutionId) {
            return;
        }
        app(PermissionRegistrar::class)->setPermissionsTeamId($this->selectedInstitutionId);
        $user = User::findOrFail($userId);
        // Ensure user role exists and is assigned when managing granular permissions
        if (! $user->hasRole('admin')) {
            if (! $user->hasRole('user')) {
                $user->assignRole('user');
            }
        }
        $perm = "letters.$type.$action";
        if ($checked) {
            $user->givePermissionTo($perm);
        } else {
            $user->revokePermissionTo($perm);
        }
        $this->dispatch('user-updated');
    }

    public function render()
    {
        $institutions = Institution::orderBy('name')->get();

        // Ensure reads use team context
        if (! is_null($this->selectedInstitutionId)) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($this->selectedInstitutionId);
        }

        $query = User::query();
        if (is_null($this->selectedInstitutionId)) {
            $query->whereNull('institution_id');
        } else {
            $query->where('institution_id', $this->selectedInstitutionId);
        }

        $users = $query
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.superadmin.user-manager', [
            'users' => $users,
            'institutions' => $institutions,
            'roles' => Role::query()->whereIn('name', ['admin', 'user'])->get(),
            'types' => [
                'external' => 'واردة خارجية',
                'internal' => 'واردة داخلية',
                'memo' => 'مذكرات',
                'personal_request' => 'طلبات الموظفين الشخصية',
                'outgoing' => 'الصادرة',
            ],
        ]);
    }
}
