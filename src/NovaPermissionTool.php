<?php

namespace Vyuldashev\NovaPermission;

use Gate;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaPermissionTool extends Tool
{
    public string $roleResource = Role::class;
    public string $permissionResource = Permission::class;

    public string $rolePolicy = RolePolicy::class;
    public string $permissionPolicy = PermissionPolicy::class;

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::resources([
            $this->roleResource,
            $this->permissionResource,
        ]);

        Gate::policy(config('permission.models.permission'), $this->permissionPolicy);
        Gate::policy(config('permission.models.role'), $this->rolePolicy);
    }

    public function roleResource(string $roleResource): NovaPermissionTool
    {
        $this->roleResource = $roleResource;

        return $this;
    }

    public function permissionResource(string $permissionResource): NovaPermissionTool
    {
        $this->permissionResource = $permissionResource;

        return $this;
    }

    public function rolePolicy(string $rolePolicy): NovaPermissionTool
    {
        $this->rolePolicy = $rolePolicy;

        return $this;
    }

    public function permissionPolicy(string $permissionPolicy): NovaPermissionTool
    {
        $this->permissionPolicy = $permissionPolicy;

        return $this;
    }

    public function menu(Request $request)
    {
        $menu = [];
        if (Gate::allows('viewAny', Permission::getModel())) {
            array_push($menu, MenuItem::resource($this->permissionResource));
        }
        if (Gate::allows('viewAny', Role::getModel())) {
            array_push($menu, MenuItem::resource($this->roleResource));
        }

        return MenuSection::make(
            'Roles & Permissions',
            $menu,
            'lock-open'
        );
    }
}
