<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Pdv;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if ($user->hasRole('administrador')) {
            $users = User::with('pdv.zonal', 'roles')->orderBy('id', 'desc')->paginate(10);
            $pdvs = Pdv::join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
                ->orderBy('zonals.name', 'asc')
                ->select('pdvs.*')
                ->with('zonal')
                ->get();
            $roles = Role::all();
            return Inertia::render('User/User/Index', compact('users', 'pdvs', 'roles'));
        } else {
            return Inertia::render('Errors/403');
        }
    }

    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['role']);
            $user = User::create($data);
            $user->assignRole($request->input('role'));
            return redirect()->route('user.index')->with('toast', ['Usuario creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error al crear el usuario!', 'danger']);
        }
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $user->update($request->validated());
            $user->syncRoles($request->input('role'));
            return redirect()->route('user.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el usuario!', 'danger']);
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $user->delete();
            return redirect()->route('user.index')->with('toast', ['Usuario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El usuario no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el usuario!', 'danger']);
            }
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');

        $users = User::with('pdv.zonal', 'roles')
            ->where('name', 'like', '%' . $texto . '%')
            ->orWhere('email', 'like', '%' . $texto . '%')
            ->orWhereHas('pdv', function ($query) use ($texto) {
                $query->where('spot', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('pdv.zonal', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orWhereHas('roles', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->appends(['texto' => $texto]);

        $pdvs = Pdv::join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
            ->orderBy('zonals.name', 'asc')
            ->select('pdvs.*')
            ->with('zonal')
            ->get();
        $roles = Role::all();

        return Inertia::render('User/User/Index', compact('users', 'texto', 'pdvs', 'roles'));
    }
}
