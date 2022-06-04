<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use App\Services\UserServiceInterface;

use App\Traits\ImageTrait;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Detail;

class UserService implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\Models\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  string $method
     * @return array
     */
    public function rules($method = null)
    {
        if($method == 'POST') {
            return [
                'role_id' => 'required|numeric|exists:roles,id',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ];
        }
        else if($method == 'PATCH') {
            $id = $this->request->user_id;
            
            return [
                'role_id' => 'nullable|numeric|exists:roles,id',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => "required|string|max:255|unique:users,username,{$id},id",
                'email' => "required|string|email|max:255|unique:users,email,{$id},id",
                'password' => 'nullable|string|min:8|confirmed',
                'photo' => 'nullable|image|max:2000',
                'salary' => 'nullable|numeric|max:99999',
                'address' => 'nullable|string|max:100',
                'mobile' => 'nullable|string|max:20',
                'tele' => 'nullable|string|max:20',
                'skills' => 'nullable|array|max:5',
            ]; 
        }
        else if($method == 'PUT') {
            return [
                'photo' => 'nullable|image|max:2000',
            ];
        }
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(int $id=NULL)
    {
        $users = $this->model->withoutTrashed();

        if($id) $users = $users->where('id' ,$id);

        return $users->paginate(10);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        $user = new $this->model($attributes);
        $user->save();

        $user->roles()->attach($attributes['role_id']);

        $role = Role::find((int) $attributes['role_id']);
        if($role->name == 'ADMIN')
            $permission_ids = Permission::whereIn('name',['create','update','destroy'])->get();
        
        else if($role->name == 'EMPLOYEE') 
            $permission_ids = Permission::whereIn('name',['update'])->get();
        
        $user->permissions()->attach($permission_ids);

        return $user;
    }

    /**
     * Retrieve user resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\User|null
     */
    public function find(int $id):? User
    {       
        return $this->model->findOrFail($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        $user = $this->model->find($id);
        
        if(isset($attributes['password']))
            $user->password = $attributes['password'];
            
        if(isset($attributes['photo'])) {
            $user->deleteImageIfExist();
            $user->addFile($attributes['photo']);
        }
        
        if(isset($attributes['role_id'])) {
            $user->roles()->sync($attributes['role_id']);

            $role = Role::find((int) $attributes['role_id']);
            if($role->name == 'ADMIN')
                $permission_ids = Permission::whereIn('name',['create','update','destroy'])->get();
            
            else if($role->name == 'EMPLOYEE') 
                $permission_ids = Permission::whereIn('name',['update'])->get();
        
            $user->permissions()->sync($permission_ids);
        }

        $data = collect($attributes)->except(['password'])->toArray();
        $isUpdate = $user->update($data);

        if($isUpdate) $user->addAction('UPDATE');

        if(isset($attributes['skills'])) $user->skills()->sync($attributes['skills']);

        Detail::updateOrCreate(['user_id' => $user->id] ,$attributes);

        return $isUpdate;
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id)
    {
        $query = $this->model->withTrashed();

        if(gettype($id) == 'integer')
            $query = $query->where('id' ,(int) $id);
        else $query = $query->whereIn('id' ,$id);

        $query->delete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
        return $this->model->onlyTrashed()->paginate(10);
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        $query = $this->model->withTrashed();

        if(gettype($id) == 'integer')
            $query = $query->where('id' ,(int) $id);
        else $query = $query->whereIn('id' ,$id);

        $query->restore();
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        $query = $this->model->withTrashed();

        if(gettype($id) == 'integer')
            $query = $query->where('id' ,(int) $id);
        else $query = $query->whereIn('id' ,$id);

        $query = $query->find($id);

        $query->deleteImageIfExist();
        $query->forceDelete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string 
    {
        return Hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        $dir = ImageTrait::$dir;
        $uniq = time();
        $file_name = "{$uniq}.{$file->extension()}";
        $path = "{$dir}/$file_name";

        $file->storeAs($dir,$file_name);

        return $file_name;
    }
}
