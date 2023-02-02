<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Store;
use App\Models\Charity;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToUser;
use App\Jobs\SendEmailToAdmin;
use App\Http\Controllers\Controller;


class PagesController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (empty($page)) abort(404);

        if ($slug == 'vouchers') {
            $stores = Store::has('vouchers')->latest()->paginate(10);
            $term = null;

            return view('frontend.pages.vouchers', compact('stores', 'term', 'page'));
        }

        if ($slug == 'donate-to-charity') {
            $HomePageCharities = Charity::where('status', '=', '1')->orderBy('id', 'DESC')->paginate(10);

            return view('frontend.pages.charities', compact('page', 'HomePageCharities'));
        }

        if ($slug == 'trending') {
            $stores =  Store::has('clicks')->with('clicks')->get()->sortByDesc(function ($store) {
                return $store->clicks->count();
            });

            return view('frontend.pages.trending', compact('page', 'stores'));
        }

        if (view()->exists("frontend.pages.{$slug}")) {
            return view("frontend.pages.{$slug}", compact('page'));
        }

        return view('frontend.pages.single-page', compact('page'));
    }

    public function offers()
    {
        $stores = Store::latest()->get();
        return view('frontend.pages.offers', compact('stores'));
    }

    public function topStores()
    {
        $stores = Store::withCount('clicks')
            ->orderBy('clicks', 'desc')->paginate(20);
        return view('frontend.pages.top_cashback', compact('stores'));
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
        $search = $request->input('search');
        if ($search) {
            $stores->where('name', 'like', '%' . $request->input('search') . '%')
                ->orWhereHas('storeRuleData', function ($query) use ($search) {
                    $query->where('key', 'meta:keywords')->where('value', 'like', '%' . $search . '%');
                });
            $term = $request->input('search');
        }
        $stores = $stores->latest()->paginate(20);

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
        $this->validate($request, [
            'g-recaptcha-response' => 'required|captcha',
        ]);

        try {
            ContactForm::create($request->all());

            $adminEmailTemplateKey = 'admin_new_contact';
            $userEmailTemplateKey = 'user_new_contact';

            $data = $request->all();

            SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables = [], $requestFilteredMessage = []);
            SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables = [], $requestFilteredMessage = []);

            return redirect()->back()->with('success', 'Thanks for contact us.');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
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

    public function showCharity(Request $request)
    {
        $charity = Charity::with('charity_type')->find($request->id);
        return view('frontend.pages.charity_model', compact('charity'));
    }
}
