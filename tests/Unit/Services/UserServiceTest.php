<?php


namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UserController;

use Tests\TestCase;

use App\Services\UserService;

use App\Models\User;
use App\Models\Role;
use App\Models\Skill;

use Str;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserServiceTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    protected $fakeUser ,$userService;

    /** 
     * Get a user service with fake param.
     * @return App\Services\UserService
     */
    public function get_user_service()  
    {
        $user = User::factory()->create();
        $this->fakeUser = $user;

        Auth::attempt(['email' => $this->fakeUser->email ,'password' => '123456']);

        $request = $this->createMock(Request::class);
        
        return new UserService($user,$request);
    }

    /** 
     * Set user service to all other classes.
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->get_user_service();
        $userController = new UserController($this->userService);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        // Actions
        $Collection = $this->userService->list();

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $Collection);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $password = Str::random(10);
        $attributes = [
            'role_id' => '1',
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password, 
            'password_confirmation' => $password,
        ];

        // Actions
        $Collection = $this->userService->store($attributes);

        // Assertions
        $this->assertInstanceOf(User::class, $Collection);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Actions
        $Collection = $this->userService->find($this->fakeUser->id);

        // Assertions
        $this->assertInstanceOf(User::class, $Collection);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $image = UploadedFile::fake()->image('file.png', 600, 600);
        $password = Str::random(10);
        $attributes = [
            'role_id' => '1',
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password, 
            'password_confirmation' => $password,
            'photo' => $image,
            'salary' => $this->faker->numerify('####'),
            'address' => $this->faker->address,
            'mobile' => $this->faker->phoneNumber,
            'tele' => $this->faker->numerify('##########'),
            'skills' => Skill::limit(3)->get()->pluck('id'),
        ];

        // Actions
        $isUpdate = $this->userService->update($this->fakeUser->id ,$attributes);

        // Assertions
        $this->assertTrue($isUpdate);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Actions
        $destroy = $this->userService->destroy($this->fakeUser->id);

        // Assertions
        $this->assertEmpty($destroy);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        // Actions
        $Collection = $this->userService->listTrashed();
        
        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class ,$Collection);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Actions
        $destroy = $this->userService->restore($this->fakeUser->id);

        // Assertions
        $this->assertEmpty($destroy);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Actions
        $destroy = $this->userService->delete($this->fakeUser->id);

        // Assertions
        $this->assertEmpty($destroy);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // Arrangements
        $image = UploadedFile::fake()->image('file.png', 600, 600);

        // Actions
        $upload = $this->userService->upload($image);

        // Assertions
        $this->assertIsString($upload);
    }

	/**
	 * @test
	 * @return void
	 */
	public function admins_can_delete_user()
	{
        $role_id = Role::where('name' ,'ADMIN')->first()->id ?? NULL;
        $this->fakeUser->roles()->attach($role_id);
        $id = $this->fakeUser->id;
        
        $respond = $this->json('POST' ,route('users.destroy' ,$id) ,[
            '_token' => csrf_token(),
            '_method' => 'DELETE'
        ]);
        
        $this->assertTrue($respond->getStatusCode() == 302);
	}

	/**
	 * @test
	 * @return void
	 */
	public function regular_user_cannot_delete_user()
	{
        $role_id = Role::where('name' ,'EMPLOYEE')->first()->id ?? NULL;
        $this->fakeUser->roles()->attach($role_id);
        $id = $this->fakeUser->id;
        
        $respond = $this->json('POST' ,route('users.destroy' ,$id) ,[
            '_token' => csrf_token(),
            '_method' => 'DELETE'
        ]);
        
        $this->assertTrue($respond->getStatusCode() == 404);
	}
}

