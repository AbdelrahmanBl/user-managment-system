<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ActionService;

class ActionController extends Controller
{
    protected $actionService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActionService $actionService)  
    {
        $this->middleware('auth');
        $this->middleware('auth.trashed');
        $this->middleware('adminstrator');
        $this->actionService = $actionService;
    }

    /**
     * Show the users data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $actions = $this->actionService->list();

        return view('actions.index' ,compact('actions'));
    }
}
