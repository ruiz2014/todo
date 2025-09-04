<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin\Role;
use App\Models\Admin\Local;
use App\Models\Admin\BelongLocal;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Staff\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // DB::enableQueryLog();
        $text = $request->search;
        
        $select = ['users.id', 'users.name', 'users.email', 'r.name as role'];
        $where = ['users.company_id'=> ['=', $request->session()->get('company_id')]];
        $orWhere = ['users.name'=>['like', '%'.$text.'%'], 'users.email' => ['like', '%'.$text.'%'], 'r.name' => ['like', '%'.$text.'%']];
        $join = ['roles as r' => ['users.rol', '=', 'r.id'] ];
        
        $query  = User::select($select);
// dd($query);
        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $users = $result->paginate(3);

        // dd($users);

        // foreach($join as $table => $row){
        //         $query->join($table, $row[0], $row[1], $row[2]);
        // }

        // foreach($where as $field => $condition){ 
        //     $query->where($field, $condition[0], $condition[1]);
        // }

        // $query->where(function($query) use ($orWhere, $text){
        //             foreach($orWhere as $column => $value){
        //                 $query->orWhere($column, $value[0], $value[1]);
        //             }
        //         });

        // $users = $query->paginate(3);

        // dd(DB::getQueryLog());

        return view('user.index', compact('users', 'text'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());

            // $users  = User::select('users.id', 'users.name', 'users.email', 'r.name as role')
            //   ->join('roles as r', 'users.rol', '=', 'r.id');
            // ->where('users.company_id', $request->session()->get('company_id'))
            // ->where("users.name", "like", "%$text%")
            // ->orWhere("users.email", "like", "%$text%")
            // ->orWhere("r.name", "like", "%$text%")
            // ->paginate(3);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();

        return view('user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $establishments = Establishment::where('company_id', request()->session()->get('company_id'))->get();
        
        $workplace = BelongLocal::where('user_id', $user->id)->first(['establishment_id', 'local_id']);
        //$workplace = isset($workplace) ? $workplace : new BelongLocal();
        $roles = isset($workplace->establishment_id) ? Role::roles()->where('establishment_id', $workplace->establishment_id)->get() : Role::roles()->get();
        $locals = Local::all();

        if(isset($workplace->establishment_id)){
            $roles = Role::roles()->where('establishment_id', $workplace->establishment_id)->get();
            $locals = $workplace->establishment_id == 2 ? Warehouse::select('warehouse_name as local_name', 'id')->where('id', $workplace->local_id )->get() : Local::all();
        }
        else{
            $roles = Role::roles()->get();
        }
        // $roles= Role::roles()->get();
        // dd($user, $workplace,Role::roles(), $locals);
        
        return view('user.edit', compact('user', 'roles', 'locals', 'establishments', 'workplace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try{

            $user->update($request->validated());

            if(is_numeric(request('local'))){
                
                BelongLocal::updateOrCreate(['user_id' =>  $user->id], ['company_id'=>request()->session()->get('company_id'), 'establishment_id'=>request('establishment'), 'local_id'=>request('local')]);
            }

            return Redirect::route('users.index')
                ->with('success', 'User updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }         
    }

    public function updateAdmin(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        try{

            $user->update($validated);

            return Redirect::route('users.index')->with('success', 'User updated successfully');
            
        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
