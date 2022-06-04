<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;

use App\Services\UserService;

use App\Models\User;
use App\Models\Role;
use App\Models\Skill;

class UserController extends Controller
{
    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->middleware('auth.trashed');
        $this->middleware('adminstrator')->only(['create','store','trashed','destroy','restore','delete']);
        $this->userService = $userService;
    }

    /**
     * Show the users data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = auth()->user()->IsAdmin ? NULL : auth()->user()->id;

        $users = $this->userService->list($id);
        
        return view('users.index' ,compact('users'));
    }

    /**
     * Show the trashed users data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function trashed()
    {
        $users = $this->userService->listTrashed();
         
        return view('users.trashed' ,compact('users'));
    }

    /**
     * Show a user data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(int $id)
    {
        $this->checkRole($id);

        $user = $this->userService->find($id);
        
        $user->addAction('SHOW');
        
        return view('users.show' ,compact('user'));
    }

    /**
     * Create the user page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()  
    {
        $roles = Role::get();

        return view('users.create' ,compact('roles'));
    }

    /**
     * Edit the user page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $user)  
    {
        $this->checkRole($user->id);
        $this->authorize('update' ,$user);

        $user  = $this->userService->find($user->id); 
        $roles = Role::get();
        $skills = Skill::get();

        return view('users.edit' ,compact('roles','skills','user'));
    }

    /**
     * Store the user method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function store(UserRequest $request)
    {
        $this->userService->store($request->all());
        
        return redirect(route('users.index'));
    }

    /**
     * Update the user method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function update(UserRequest $request ,User $user)
    {
        $this->checkRole($user->id);
        $this->authorize('update' ,$user);
        
        $this->userService->update($user->id ,$request->all());
        
        return redirect(route('users.index'));
    }

    /**
     * Destroy the user method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function destroy(int $id)
    {
        $this->userService->destroy($id);

        return redirect(route('users.index'));
    }

    /**
     * Restore a trashed user method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function restore(int $id)
    {
        $this->userService->restore($id);
         
        return redirect(route('users.trashed'));
    }

    /**
     * Delete a trashed user permenant method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function delete(int $id)
    {
        $this->userService->delete($id);
         
        return redirect(route('users.trashed'));
    }

    /**
     * Upload an image of user method.
     *
     * @return \Illuminate\Contracts\Support\Redirect
     */
    public function upload(UserRequest $request, int $id)
    {
        $this->checkRole($id);

        $user = $this->userService->find($id);
        $user->deleteImageIfExist();
        $user->addFile($request->file('photo'));
        $user->save();
         
        return redirect(route('users.index'));
    }

    /**
     * Get id of user if not admin.
     *
     * @return int|null
     */
    public function checkRole(int $id)  
    {
        if(!auth()->user()->IsAdmin && auth()->user()->id !== $id)
            abort(401);
    }
}
