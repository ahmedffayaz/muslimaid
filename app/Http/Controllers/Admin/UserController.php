<?php

namespace App\Http\Controllers\Admin;

use  Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\PaymentInfo;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:view users', ['only' => ['index']]);
         $this->middleware('permission:edit users', ['only' => ['edit','show','update']]);
         $this->middleware('permission:add users', ['only' => ['create','Store']]);
         $this->middleware('permission:delete users', ['only' => ['destroy']]);
         $this->middleware('permission:change password', ['only' => ['changePassword','savePassword']]);
         $this->middleware('permission:edit payment info', ['only' => ['paymentInfo','paymentSave']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $users = User::role('user')->orderBy('id', 'DESC')->paginate(30);
        return view('admin-dashboard.users.index', compact('users','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-dashboard.users.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user =  User::create([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make('123456789'),
            'registration_type'=>'sign up',
            'phone' => $request->phone,
            'address' => $request->address,
            'intro' => $request->intro
        ]);

        $user->assignRole('user');
        flash()->success('New user added successfully');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $users = User::role('user')->latest()->get();
        $roles = Role::all();
        return view('admin-dashboard.users.show', compact('user','users','roles'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin-dashboard.users.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'intro' => $request->intro,
            'status' => $request->status,
        ]);

        $user->assignRole($request->roles);

        if(!$request->ajax()){
            flash()->success('User updated successfully');
            return redirect()->route('admin.users.index');
        }else{
            return true;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->paymentInfo){
            $user->paymentInfo->delete();

        }
        $user->delete();

        flash()->success('User deleted successfully');
        return redirect()->route('admin.users.index');
    }
    function fetch(Request $request)
    {
        if($request->ajax())
        {
            $route="index";
            $users = User::role('user')->orderBy('id', 'DESC')->paginate(30);

            return view('admin-dashboard.users.index_data', compact('users','route'))->render();
        }
    }
    public function exportCsv(Request $request)
    {
        try {
            
            $table = User::role('user')->latest()->get();
            $filename = "users.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('First Name', 'Last Name','Cashback Amount', 'Reg Type','Reg date', 'Status'));

            foreach($table as $row) {
                fputcsv($handle, array($row->first_name,
                                        $row->last_name,
                                        '0',
                                        $row->registration_type, 
                                        $row->created_at,
                                        $row->status ? 'active' : 'in-active'));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return Response::download($filename, 'users.csv', $headers);

        } catch (\Throwable $th) {

            flash()->error('Error while exporting the users');
            return redirect()->route('admin.users.index');

        }
        
    }

    public function paymentInfo(User $user)
    {
        return view('admin-dashboard.users.payment_info',compact('user'));
    }


    public function paymentSave(Request $request){

        $payment = PaymentInfo::updateOrCreate([
            'user_id'   => $request->user_id,
        ],$request->all());

        // $payment = PaymentInfo::create($request->all());

        if(!$request->ajax())
        {
            flash()->success('Payment method added successfully');
            return redirect()->route('admin.users.index');
        }else{
            return array('message'=>'payment method saved',
                        'updated'=>'success');
        }
        
        

    }
    public function changePassword(User $user)
    {
        return view('admin-dashboard.users.password',compact('user'));
    }
    public function savePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
           

            if(!$request->ajax())
            {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            }else{
                return array('message' => $validator->errors()->first(),
                                'updated'=>'error');
            }

        }
        $user->update([
            'password' => Hash::make($request->password),

        ]);
        if(!$request->ajax())
        {
        flash()->success('Password changed successfully');
        return redirect()->route('admin.users.index');
        }else{
            return array('message' => 'Password updated successfully',
                                'updated'=>'success');
        }

    }
    public function searchUsers(Request $request, User $users)
    {
        // dd($request->all());
        $users = $users->newQuery();

        // Search by type.
        // if ($request->input('type')!=-1) {
        //     $users->where('registration_type', $request->input('type'));
        // }

        // Search by store name/ID.
        if ($request->input('name')) {
            $users->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%'.$request->input('name').'%');
            $users->orWhere('id',$request->input('name'));
           
        }

        if($request->input('email')){
            $users->Where('email','like', '%'.$request->input('email').'%');
        }

        // Search by status.
        if ($request->input('status')!=-1) {
            $users->where('status', $request->input('status'));
        }
        
        $users = $users->role('user')->orderBy('id', 'DESC')->paginate(30);
        $route='search';
        return view('admin-dashboard.users.index_data', compact('users','route'))->render();
    }

    function fetchCashbacks(Request $request)
    {
        if($request->ajax())
        {
            $cashbacks = UserCashback::where('user_id',$request->user)->latest()->paginate(20);
            return view('admin-dashboard.users.cashbacks', compact('cashbacks'))->render();
        }
    }
    function fetchClicks(Request $request)
    {
        if($request->ajax())
        {
            $clicks = ExitClick::where('user_id',$request->user)->latest()->paginate(20);
            return view('admin-dashboard.users.clicks', compact('clicks'))->render();
        }
    }
    public function showUser()
    {
      
        return view('admin-dashboard.users.show');
    }

}
