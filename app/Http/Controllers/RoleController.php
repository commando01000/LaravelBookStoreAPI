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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all roles
        $roles = Role::all();

        // Return the roles as a JSON response
        return new RolesResource($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        // Create the role
        $role = Role::create([
            'name' => $request->name,
        ]);

        // If permissions are provided, assign them to the role
        if ($request->has('permissions') && $request->name !== 'admin') {
            $permissions = $request->permissions;
            foreach ($permissions as $permissionName) {
                // Create the permission if it doesn't exist
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                // Assign the permission to the role
                $role->givePermissionTo($permission);
            }
        }

        // Optionally, for the 'admin' role, assign all permissions
        if ($request->name === 'admin') {
            // Get all permissions
            $allPermissions = Permission::all();
            // Assign all permissions to the role
            $role->syncPermissions($allPermissions);
        }

        // Assign the created role to the current authenticated user
        $user = auth()->user();
        /** @var App\Models\User $user */

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        // Assign the role to the user
        $user->assignRole($role);

        // Return the created role as a JSON response
        return response(new RolesResource($role), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        // Return the role as a JSON response
        return new RolesResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        // Update the role
        $role->update([
            'name' => $request->name,
        ]);

        // If permissions are provided, assign them to the role
        if ($request->name === 'admin') {
            // Get all permissions
            $allPermissions = Permission::all();
            // Assign all permissions to the role
            $role->syncPermissions($allPermissions);
        }

        if ($request->has('permissions')) {
            $permissions = $request->permissions;
            foreach ($permissions as $permissionName) {
                // Create the permission if it doesn't exist
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                // Assign the permission to the role
                $role->givePermissionTo($permission);
            }
        }

        // Return the updated role as a JSON response
        return response(new RolesResource($role), 200);
    }

    public function getRolesWithPermissions()
    {
        // Get all roles with permissions
        $roles = Role::with('permissions')->get();

        // Return the roles with permissions as a JSON response
        return new RolesResource($roles);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();

        // Return a JSON response with a success message
        return response('Deleted successfully !', 204);
    }
}
