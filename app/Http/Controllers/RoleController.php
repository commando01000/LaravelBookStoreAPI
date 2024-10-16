<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RolesResource;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return new RolesResource($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        // Create the role
        $role = Role::create([
            'name' => $request->name,
        ]);

        // If permissions are provided, assign them, otherwise skip this step
        if ($request->has('permissions') && $request->name !== 'admin') {
            $permissions = $request->permissions;
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $role->givePermissionTo($permission); // Assign the permission
            }
        }

        // Optionally, for the 'admin' role, you could give it all permissions
        if ($request->name === 'admin') {
            // Assign all permissions to admin role (if needed)
            $allPermissions = Permission::all();
            $role->syncPermissions($allPermissions);
        }

        // Assign the created role to the current authenticated user

        // find user by email
        $user = auth()->user();
        /** @var App\Models\User $user */
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->assignRole($role);

        return response(new RolesResource($role), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update([
            'name' => $request->name,
        ]);

        if ($request->name === 'admin') {
            $allPermissions = Permission::all();
            $role->syncPermissions($allPermissions);
        }

        if ($request->has('permissions')) {
            $permissions = $request->permissions;
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $role->givePermissionTo($permission);
            }
        }
        return response(new RolesResource($role), 200);
    }

    public function getRolesWithPermissions()
    {
        $roles = Role::with('permissions')->get();
        return new RolesResource($roles);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response('Deleted successfully !', 204);
    }
}
