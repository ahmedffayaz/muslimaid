<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\ExitClick;
use App\Models\PaymentInfo;
use App\Models\UserCashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Favorite;
use App\Models\Store;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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

        try {
            DB::beginTransaction();
            $avatarImage = "default.png";
            if ($request->hasFile('avatar')) {
                $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
            }

            $user =  User::create([
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make('123456789'),
                'registration_type' => 'sign up',
                'phone' => $request->phone_number,
                'address' => $request->address,
                'avatar' => $avatarImage,
                'status' => 'active',
                'date_of_birth' => $request->date_of_birth,
            ]);

            $user->assignRole('user');
            DB::commit();
            //send email to user to verify email address
            dispatch(new SendEmailJob($user));

            flash()->success('New user added successfully');
            return redirect()->route(getAdminPrefix() . '.users.index');
        } catch (Throwable $th) {
            DB::rollBack();
            flash()->error('Something went wrong, try again');
            return redirect()->back();
        }
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
            ]);

            if ($validator->fails()) {
                if (!$request->ajax()) {
                    flash()->error($validator->errors()->first());
                    return redirect()->back();
                } else {
                    return array(
                        'message' => $validator->errors()->first(),
                        'success' => false
                    );
                }
            }

            DB::beginTransaction();
            $avatarImage = $user->avatar;
            if ($request->hasFile('avatar')) {

                $avatarImage = storeUserAvatar($request->file('avatar'), $avatarImage);
            }

            $user->update([
                'first_name' => $request->input('firstname'),
                'last_name' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'address_2' => $request->input('address_line_2'),
                'street' => $request->input('street'),
                'country_id' => $request->input('country_id'),
                'postal_code' => $request->input('postal_code'),
                'status' => $request->input('status'),
                'is_email_verified' => $request->input('status') === 'pending' || $request->input('status') === 'in_active' ? 0 : 1,
                'avatar' => $avatarImage,
                'date_of_birth' => $request->input('date_of_birth'),
            ]);

            $user->syncRoles($request->input('roles'));
            DB::commit();

            if ($request->ajax()) {
                return array(
                    'message' => 'User updated successfully',
                    'success' => true
                );
            } else {
                flash()->success('User updated successfully');
                return redirect()->route(getAdminPrefix() . '.users.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
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
        return redirect()->route(getAdminPrefix() . '.users.index');
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
            return redirect()->route(getAdminPrefix() . '.users.index');
        }
    }

    public function paymentInfo(User $user)
    {
        return view('admin-dashboard.users.payment_info', compact('user'));
    }

    public function paymentSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
            'paypal_email' => $request->input('payment_method') === 'paypal' ? 'required' : '',
            'account_name' => $request->input('payment_method') === 'bank' ? 'required' : '',
            'bank_title' => $request->input('payment_method') === 'bank' ? 'required' : '',
            'account_number' => $request->input('payment_method') === 'bank' ? 'required' : '',
            'bank_sort_code' => $request->input('payment_method') === 'bank' ? 'required' : '',
            'bic' => $request->input('payment_method') === 'bank' ? 'required' : '',
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return array(
                    'message' => $validator->errors()->first(),
                    'success' => false
                );
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }
        PaymentInfo::updateOrCreate([
            'user_id'   => $request->user_id,
        ], $request->all());


        if (!$request->ajax()) {
            flash()->success('Payment method added successfully');
            return redirect()->route(getAdminPrefix() . '.users.index');
        }

        return array(
            'message' => 'payment method saved',
            'success' => true
        );
    }

    public function changePassword(User $user)
    {
        return view('admin-dashboard.users.password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {
        $rules = [
            'password' => ['required', 'confirmed']
        ];

        $passwordRules = env('PASSWORD_VALIDATION', '');
        if(!empty($passwordRules)){
            $additionalRules = explode('|', $passwordRules);
            $rules['password'] = array_merge($rules['password'], $additionalRules);
        }else {
            $rules['password'][] = 'string';
        }

        $validator = Validator::make($request->all(), $rules);

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
            return redirect()->route(getAdminPrefix() . '.users.index');
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

    function fetchMetaData(Request $request)
    {
        if (!$request->ajax()) return;

        $metaDatas = UserMeta::where('user_id', $request->user)->latest()->paginate(20);
        return view('admin-dashboard.users.meta-data', compact('metaDatas'))->render();
    }

    public function showUser()
    {
        return view('admin-dashboard.users.show');
    }
}
