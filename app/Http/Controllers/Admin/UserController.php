<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\PaymentInfo;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use File;
use Storage;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view users', ['only' => ['index']]);
        $this->middleware('permission:edit users', ['only' => ['edit', 'show', 'update']]);
        $this->middleware('permission:add users', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete users', ['only' => ['destroy']]);
        $this->middleware('permission:change password', ['only' => ['changePassword', 'savePassword']]);
        $this->middleware('permission:edit payment info', ['only' => ['paymentInfo', 'paymentSave']]);
    }

    public function index()
    {
        $route = 'index';
        $users = User::role('user')->orderBy('id', 'DESC')->paginate(30);

        return view('admin-dashboard.users.index', compact('users', 'route'));
    }

    public function create()
    {
        return view('admin-dashboard.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            if (!$request->ajax()) {
                flash()->error($validator->errors()->first());
                return redirect()->back()->withInput();
            }

            return array(
                'message' => $validator->errors()->first(),
                'updated' => 'error'
            );
        }
        $avatar_image = "default.png";
        if ($request->hasFile('avatar')) {
            $avatar_image = store_user_avatar($request->file('avatar'), $avatar_image);
        }
    
        $user =  User::create([
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make('123456789'),
            'registration_type' => 'sign up',
            'phone' => $request->phone,
            'address' => $request->address,
            'intro' => $request->intro,
            'avatar' => $avatar_image
        ]);

        $user->assignRole('user');

        flash()->success('New user added successfully');
        return redirect()->route('admin.users.index');
    }


    public function show(User $user)
    {
        $users = User::role('user')->latest()->get();
        $roles = Role::all();

        return view('admin-dashboard.users.show', compact('user', 'users', 'roles'));
    }

    public function edit(User $user)
    {
        return view('admin-dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                if (!$request->ajax()) {
                    flash()->error($validator->errors()->first());
                    return redirect()->back();
                }

                return array(
                    'message' => $validator->errors()->first(),
                    'success' => false
                );
            }

            $avatar_image = $user->avatar;
            if ($request->hasFile('avatar')) {
                $avatar_image = store_user_avatar($request->file('avatar') , $avatar_image);
            }

            $user->update([
                'first_name' => $request->input('firstname'),
                'last_name' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'intro' => $request->input('intro'),
                'status' => $request->input('status'),
                'avatar' => $avatar_image
            ]);

            $user->syncRoles($request->input('roles'));

            if (!$request->ajax()) {
                flash()->success('User updated successfully');
                return redirect()->route('admin.users.index');
            }

            return array(
                'message' => $validator->errors()->first(),
                'success' => true
            );
        } catch (Exception $e) {
            if (!$request->ajax()) throw new Exception($e->getMessage());

            return array(
                'message' => $e->getMessage(),
                'success' => false
            );
        }
    }

    public function destroy(User $user)
    {
        if ($user->paymentInfo) {
            $user->paymentInfo->delete();
        }

        $user->delete();

        flash()->success('User deleted successfully');
        return redirect()->route('admin.users.index');
    }

    function fetch(Request $request)
    {
        if (!$request->ajax()) return;

        $route = "index";
        $users = User::role('user')->orderBy('id', 'DESC')->paginate(30);

        return view('admin-dashboard.users.index_data', compact('users', 'route'))->render();
    }

    public function exportCsv(Request $request)
    {
        try {
            $table = User::role('user')->latest()->get();
            $filename = "users.csv";

            $handle = fopen($filename, 'w+');

            fputcsv($handle, array('First Name', 'Last Name', 'Cashback Amount', 'Reg Type', 'Reg date', 'Status'));

            foreach ($table as $row) {
                fputcsv($handle, array(
                    $row->first_name,
                    $row->last_name,
                    '0',
                    $row->registration_type,
                    $row->created_at,
                    $row->status ? 'active' : 'in-active'
                ));
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
        return view('admin-dashboard.users.payment_info', compact('user'));
    }

    public function paymentSave(Request $request)
    {
        PaymentInfo::updateOrCreate([
            'user_id'   => $request->user_id,
        ], $request->all());

        if (!$request->ajax()) {
            flash()->success('Payment method added successfully');
            return redirect()->route('admin.users.index');
        }

        return array(
            'message' => 'payment method saved',
            'updated' => 'success'
        );
    }

    public function changePassword(User $user)
    {
        return view('admin-dashboard.users.password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            if (!$request->ajax()) {
                flash()->error($validator->errors()->first());
                return redirect()->back();
            }

            return array(
                'message' => $validator->errors()->first(),
                'updated' => 'error'
            );
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if (!$request->ajax()) {
            flash()->success('Password changed successfully');
            return redirect()->route('admin.users.index');
        }

        return array(
            'message' => 'Password updated successfully',
            'updated' => 'success'
        );
    }

    public function searchUsers(Request $request, User $users)
    {
        $users = $users->newQuery();

        // Search by store name/ID.
        if ($request->input('name')) {
            $users->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $request->input('name') . '%');
            $users->orWhere('id', $request->input('name'));
        }

        if ($request->input('email')) {
            $users->Where('email', 'like', '%' . $request->input('email') . '%');
        }

        // Search by status.
        if ($request->input('status') != -1) {
            $users->where('status', $request->input('status'));
        }

        $users = $users->role('user')->orderBy('id', 'DESC')->paginate(30);
        $route = 'search';

        return view('admin-dashboard.users.index_data', compact('users', 'route'))->render();
    }

    function fetchCashbacks(Request $request)
    {
        if (!$request->ajax()) return;

        $cashbacks = UserCashback::where('user_id', $request->user)->latest()->paginate(20);
        return view('admin-dashboard.users.cashbacks', compact('cashbacks'))->render();
    }

    function fetchClicks(Request $request)
    {
        if (!$request->ajax()) return;

        $clicks = ExitClick::where('user_id', $request->user)->latest()->paginate(20);
        return view('admin-dashboard.users.clicks', compact('clicks'))->render();
    }

    public function showUser()
    {
        return view('admin-dashboard.users.show');
    }
}
