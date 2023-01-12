<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Store;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\ContactForm;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Harimayco\Menu\Models\Menus;
use App\Http\Controllers\Controller;
use App\Models\Charity;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($slug == 'offers') {
            return view('frontend.pages.offers', compact('page'));
        }
        if ($slug == 'contact') {
            return view('frontend.pages.contact', compact('page'));
        }
        if ($slug == 'about') {
            return view('frontend.pages.about', compact('page'));
        }
        if ($slug == 'vouchers') {
            $stores = Store::has('vouchers')->latest()->paginate(10);
            $term = null;
            return view('frontend.pages.vouchers', compact('stores', 'term', 'page'));
        }
        $HomePageCharities=Charity::where('status','=','1')->orderBy('id', 'DESC')->paginate(10);
        return view('frontend.pages.single_page', compact('page','HomePageCharities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function offers()
    {
        $stores = Store::latest()->get();
        return view('frontend.pages.offers', compact('stores'));
    }


    public function cashbackByCategory($slug)
    {

        $category = Category::where('slug', $slug)->first();
        $stores = $category->stores()->paginate(20);
        return view('frontend.pages.cashback_by_category', compact('stores', 'category'));
    }
    public function topStores()
    {
        $stores = Store::withCount('clicks')
            ->orderBy('clicks', 'desc')->paginate(20);
        return view('frontend.pages.top_cashback', compact('stores'));
    }

    public function trending()
    {

        $stores =  Store::has('clicks')->with('clicks')->get()->sortByDesc(function ($store) {
            return $store->clicks->count();
        });
        return view('frontend.pages.trending', compact('stores'));
    }
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function blog()
    {
        $blogs = Blog::latest()->get();
        return view('frontend.pages.blog', compact('blogs'));
    }

    public function login(Request $request)
    {
        session(['prvUrl' => $request->get('prvUrl')]);
        return view('auth.login');
    }

    public function register(Request $request)
    {
        session(['prvUrl' => $request->get('prvUrl')]);
        return view('auth.register');
    }

    public function search(Request $request, Store $stores)
    {
        $stores = $stores->newQuery();
        $term = null;
        if ($request->input('search')) {
            $stores->where('name', 'like', '%' . $request->input('search') . '%');
            $term = $request->input('search');
        }
        $stores = $stores->latest()->paginate(20);
        // dd($stores);
        return view('frontend.pages.search', compact('stores', 'term'));
    }

    public function searchSuggestions(Request $request, Store $stores)
    {
        $stores = $stores->newQuery();
        $term = null;
        if ($request->input('term')) {
            $stores->where('name', 'like', '%' . $request->input('term') . '%');
            $term = $request->input('term');
        }
        $stores = $stores->latest()->get();
        return view('frontend.components.search_suggestions', compact('stores', 'term'))->render();
    }

    public function vouchers()
    {
        $stores = Store::has('vouchers')->latest()->paginate(10);
        $term = null;
        return view('frontend.pages.vouchers', compact('stores', 'term'));
    }

    public function blogPost($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $blogs = Blog::latest()->get();

        return view('frontend.pages.single_blog', compact('blog', 'blogs'));
    }


    public function contactForm(Request $request)
    {
        // dd($request->all());
        $contact = ContactForm::create($request->all());

        $user_email_template = EmailTemplate::where('key', 'user_new_contact')->first();
        $admin_email_template = EmailTemplate::where('key', 'admin_new_contact')->first();

        $filtered_user_message  = str_replace(
            ['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{SUBJECT}}', '{{MESSAGE}}'],
            [SiteSetting()['website_title'], url('/'), $request->input('name'), $request->input('email'), $request->input('subject'), $request->input('message')],
            $user_email_template->message
        );
        $filtered_admin_message  = str_replace(
            ['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{SUBJECT}}', '{{MESSAGE}}'],
            [SiteSetting()['website_title'], url('/'), $request->input('name'), $request->input('email'), $request->input('subject'), $request->input('message')],
            $admin_email_template->message
        );

        $email_data = array(
            'name' =>  $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'email_message' => $filtered_admin_message,
            'subject' => $admin_email_template->subject
        );

        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to('admin@trs.com', $email_data['name'])
                ->subject($email_data['subject']);
        });
        $email_data = array(
            'name' =>  $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'email_message' => $filtered_user_message,
            'subject' => $user_email_template->subject
        );
        Mail::send('emails.email_template', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject($email_data['subject']);
        });
        $ticket = new Ticket([
            'title'     => $request->input('subject'),
            'user_id'   =>  isset(Auth::user()->id) ?(Auth::user()->id):0, 
           'ticket_id' => strtoupper(Str::random(12)),
            'category_id'  => '4',
            'priority'  => 'high',
            'ticket_type'  => 'ticket',
            'message'   => $request->input('message'),
            'status'    => "open",
        ]);
        $ticket->save();
        return redirect()->back()->with('success', 'Thanks for contact us.');
    }

    public function allStores()
    {
        $s = Store::latest()->get();
        $groups = $s->sortBy('name')->groupBy(function ($store) {
            return strtoupper(substr($store->name, 0, 1));
        });
        return view('frontend.pages.all_stores', compact('groups'));
    }
    public function allStoresLetter($letter)
    {
        $stores = Store::where('name', 'like', $letter . '%')->get();
        return view('frontend.pages.stores_with_letter', compact('stores', 'letter'));
    }
    
    public function showCharity(Request $request){
        $charity = Charity::with('charity_type')->find($request->id);
        return view('frontend.pages.charity_model',compact('charity'));
    
    }
}
