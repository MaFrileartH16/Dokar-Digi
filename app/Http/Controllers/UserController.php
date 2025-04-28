<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
  /**
   * Display a listing of the users.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    // Get all available roles for the create user modal
    $roles = Role::where('name', '!=', 'Admin')->get();

    // Get query parameters
    $sort = $request->input('sort', 'name');
    $direction = $request->input('direction', 'asc');
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10);

    // Validate sort field to prevent SQL injection
    $allowedSortFields = ['name', 'email', 'role'];
    if (!in_array($sort, $allowedSortFields)) {
      $sort = 'name';
    }

    // Validate direction
    if (!in_array($direction, ['asc', 'desc'])) {
      $direction = 'asc';
    }

    // Validate per_page
    $allowedPerPage = [10, 25, 50, 100];
    if (!in_array($perPage, $allowedPerPage)) {
      $perPage = 10;
    }

    // Start building the query
    $query = User::query();

    // Exclude users with Admin role
    $query->whereDoesntHave('roles', function ($q) {
      $q->where('name', 'Admin');
    });

    // Apply search if provided
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhereHas('roles', function ($roleQuery) use ($search) {
            $roleQuery->where('name', 'like', "%{$search}%");
          });
      });
    }

    // Apply sorting
    if ($sort === 'role') {
      $query->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.*')
        ->orderBy('roles.name', $direction);
    } else {
      $query->orderBy($sort, $direction);
    }

    // Get paginated results
    $users = $query->paginate($perPage)->withQueryString();

    return view('users.index', compact('users', 'roles'));
  }


  /**
   * Store a newly created user in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'confirmed', Password::defaults()],
      'role' => ['required', 'exists:roles,name'],
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);

    return redirect()->route('users.index')
      ->with('success', 'Pengguna berhasil dibuat.');
  }

  /**
   * Show the form for editing the specified user.
   *
   * @param User $user
   * @return Response
   */
  public function edit(User $user)
  {
    $roles = Role::all();
    return view('users.edit', compact('user', 'roles'));
  }

  /**
   * Update the specified user in storage.
   *
   * @param Request $request
   * @param User $user
   * @return Response
   */
  public function update(Request $request, User $user)
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
      'role' => ['required', 'exists:roles,name'],
    ];

    // Only validate password if it's provided
    if ($request->filled('password')) {
      $rules['password'] = ['confirmed', Password::defaults()];
    }

    $request->validate($rules);

    $user->update([
      'name' => $request->name,
      'email' => $request->email,
    ]);

    if ($request->filled('password')) {
      $user->update([
        'password' => Hash::make($request->password),
      ]);
    }

    // Sync roles
    $user->syncRoles([$request->role]);

    return redirect()->route('users.index')
      ->with('success', 'Pengguna berhasil diperbarui.');
  }

  /**
   * Remove the specified user from storage.
   *
   * @param User $user
   * @return Response
   */
  public function destroy(User $user)
  {
    // Prevent deleting yourself
    if ($user->id === auth()->id()) {
      return redirect()->route('users.index')
        ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    $user->delete();

    return redirect()->route('users.index')
      ->with('success', 'Pengguna berhasil dihapus.');
  }
}
