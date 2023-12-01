<?php

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use App\Models\Store;
use App\Models\Slider;
use App\Models\Ticket;
use App\Models\Cashout;
use App\Models\Charity;
use App\Models\SeoRule;
use App\Models\Category;
use App\Models\Currency;
use App\Models\UserVerify;
use App\Models\SiteSetting;
use App\Models\StoreReview;
use Illuminate\Support\Str;
use App\Models\StoreSeoData;
use App\Jobs\SendEmailToUser;
use App\Models\EmailTemplate;
use App\Jobs\SendEmailToAdmin;
use App\Models\Appeal;
use App\Models\StoreCashback;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Stevebauman\Location\Facades\Location;
use Intervention\Image\ImageManagerStatic as Image;

function getImporterYMLSettings($path)
{
    $moduleSettings  = Yaml::parseFile(base_path('modules.yml'));

    $pathParts = explode('_', $path);
    $moduleConfig = $moduleSettings;
    foreach ($pathParts as $part) {
        if (isset($moduleConfig[$part])) {
            $moduleConfig = $moduleConfig[$part];
        } else {
            return 1;
        }
    }

    if (in_array($moduleConfig, ['on', 'On', 'ON', 1])) {
        return 1;
    }

    return 0;
}

function getPageTemplates($slug)
{
    $page = Page::where('slug', $slug)->first();
    return $page;
}


function getAdminPrefix()
{
    return (env('ADMIN_PREFIX') ? env('ADMIN_PREFIX') : 'admin');
}

function getMoreCategories()
{
    $categories = Category::where(function ($query){
        $query->where('visibility', 'more');
    })->with(['childs' => function ($query) {
        $query->where('status', '1')->orderBy('sort', 'asc');
    }])->where('parent_id', 0)->where('status', '1')->orderBy('sort', 'desc')->orderBy('name', 'asc')->get();
    return $categories;
}

function checkFavorite($storeId, $header_token = null)
{
    if (empty(auth()->user())) {
        $isFavorite = [];
        if ($header_token != null) {
            [$tokenId, $tokenValue] = explode('|', $header_token);

            $personalAccessToken = PersonalAccessToken::where('id', $tokenId)->first();
            if ($personalAccessToken) {
                $user = User::with('favoriteStores')->find($personalAccessToken->tokenable_id);
                $isFavorite = $user->favoriteStores->where('id', $storeId)->pluck('id')->first();
            }
        }
    } else {
        $isFavorite = auth()->user()->favoriteStores()->where('stores.id', $storeId)->pluck('stores.id')->first();
    }
    return $isFavorite;
}


function getCuisineTags($store)
{
    $tags = $store->categories->where('parent_id', Category::where('slug', 'cuisine')->first()->id)->pluck('name')->toArray();
    return $tags;
}

function getCuisineChilds()
{
    $cuisineCategory = Category::where('slug', 'cuisine')->first();
    $childCuisines = $cuisineCategory->childs->pluck('name')->all();
    return $childCuisines;
}

function getSpecificSetting($type)
{
    $setting = SiteSetting::where('type', $type)->pluck('value')->first();
    return $setting;
}
function checkCashbackChildCategories($slug, $parentId)
{
    $category = Category::where('slug', $slug)->where('parent_id', $parentId)->whereStatus('1')->first();
    if (isset($category)) {
        return 1;
    }
    return 0;
}
function removeAllTags($text, $limit)
{
    $cleanText = strip_tags($text, '<p>');
    if ($limit != 0) {
        $cleanText = substr($cleanText, 0, $limit);
        $cleanText = str_replace('<p>', '<p class="excerpt">', $cleanText);
        if (strlen($text) > $limit) {
            $cleanText .= '..';
        }
    }
    return $cleanText;
}

function getRelatedBlogs($keywords, $id)
{
    $tags = explode(",", $keywords);
    $blogs = [];
    if (!empty($tags[0])) {
        $blogs = Blog::where('id', '!=', $id)->where(function ($query) use ($tags) {
            foreach ($tags as $tag) {
                $tag = trim($tag);
                $query->orWhere('meta_keyword', 'like', '%' . $tag . '%');
            }
        })->get();
    }
    return $blogs;
}

function statusBadges($status)
{
    if ($status == 'confirmed') {
        return '<span class="badge badge-primary">' . ucfirst($status) . '</span>';
    } elseif ($status == 'paid') {
        return '<span class="badge badge-success">' . ucfirst($status) . '</span>';
    } elseif ($status == 'failed') {
        return '<span class="badge badge-danger">' . ucfirst($status) . '</span>';
    } elseif ($status == 'pending') {
        return '<span class="badge badge-info">' . ucfirst($status) . '</span>';
    } elseif ($status == 'donated') {
        return '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    } elseif ($status == 'processing donation') {
        return '<span class="badge badge-light">' . ucfirst($status) . '</span>';
    } elseif ($status == 'processing') {
        return '<span class="badge badge-warning">' . ucfirst($status) . '</span>';
    }
    return '<span class="badge badge-primary">' . ucfirst($status) . '</span>';
}

function convertCashbackStatusToDbFormat($status)
{
    if ($status == 'Confirmed') return 3;
    if ($status == 'Paid') return 4;
    if ($status == 'Failed') return 2;
    if ($status == 'Pending') return 1;
    if ($status == 'Donated') return 7;
    if ($status == 'Processing Donation') return 6;
    if ($status == 'Processing') return 5;
    return 1;
}

function getHomeSliders()
{
    $name = "Before Login Home";
    if (auth()->user()) {
        $name = "After Login Home";
    }
    $slider = Slider::where('name', $name)->first();
    return $slider;
}

//feature store for cashblack
function getFeaturesStores($featureTag, $categorySlug = null)
{
    $stores = Store::whereHas('tags', function ($query) use ($featureTag) {
        $query->where('title', $featureTag);
    })->where('status', 'active')->withCount('cashbacks');
    if ($categorySlug != null) {
        $stores = $stores->whereHas('categories', function ($query) use ($categorySlug) {
            return $query->where('categories.slug', $categorySlug);
        });
    }
    $tagStores = $stores->latest()->get();
    return $tagStores;
}

function firstTopCategoryofStore($topStore)
{
    $category = $topStore->categories()->whereHas('tags', function ($query) {
        $query->where('title', 'top_categories');
    })->orderby('updated_at')->first();
    return $category;
}

function getFeaturesCharities($featureTag)
{
    $charities = Charity::whereHas('tags', function ($query) use ($featureTag) {
        $query->where('title', $featureTag);
    })->latest()->get();
    return $charities;
}


function getFeaturesCategories($featureTag)
{
    $categories = Category::whereHas('tags', function ($query) use ($featureTag) {
        $query->where('title', $featureTag);
    })->where(function ($query) {
        $query->where('visibility', '!=', 'hidden')
            ->orWhereNull('visibility');
    })->whereStatus('1')->latest()->get();
    return $categories;
}

function getFeaturesAppeals($featureTag)
{
    $appeals = Appeal::whereHas('tags', function ($query) use ($featureTag) {
        $query->where('title', $featureTag);
    })->latest()->get();
    return $appeals;
}

function separatePageKeywords($content)
{
    $content_keyword = explode('{{', $content);
    $keyword_array = array();
    foreach ($content_keyword as $keyword) {
        $keyword = str_replace("}}", "_KEYWORD}}", $keyword);
        $keyword_array =  array_merge($keyword_array, explode('}}', $keyword));
    }
    return $keyword_array;
}
/**
 * get User Full name
 *
 * @param $user
 * @return $userName
 */
function getFullName($user)
{
    return ucwords($user->first_name . ' ' . $user->last_name);
}

function storeUserAvatar($file, $existingFile)
{
    if ($existingFile != "default.png") {
        if (File::exists(public_path('storage/users/images/avatar/' . $existingFile))) {
            File::delete(public_path('storage/users/images/avatar/' . $existingFile));
        }
    }

    $current_timestamp = Carbon::now()->timestamp;
    $ext = $file->getClientOriginalExtension();
    $filename = 'avatar_' . $current_timestamp . '.' . $ext;
    $dir = 'storage/users/images/avatar/';
    if (!Storage::disk('public')->exists('users/images/avatar')) {
        Storage::disk('public')->makeDirectory('users/images/avatar', 0775, true); //creates directory
    }
    $avatar_image = Image::make($file)->resize(512, 512);
    $avatar_image->save($dir . $filename, 100);
    $image = File::get($dir . '/' . $filename);
    Storage::put('users/images/avatar/' . $filename, $image);
    return $filename;
}

/**
 * get User role
 *
 * @param $user
 * @return $role
 */
function getUserRole($user)
{
    $userRole = $user->roles;
    return count($userRole) > 0 ? $userRole[0] : '';
}

/**
 * convert date
 *
 * @param $date
 * @return $date
 */
function convertDate($date, $format = true)
{
    if ($date !== null) {
        if ($format) return Carbon::parse($date)->format('d M, Y');

        return Carbon::parse($date);
    }
}

/**
 * minimize text
 *
 * @param $text, $limit
 * @return $text
 */
function addEllipsis($text, $max = 30)
{
    return strlen($text) > 30 ? mb_substr($text, 0, $max, "UTF-8") . "..." : $text;
}

/**
 * @param $status
 * @return string
 */
function statusClasses($status)
{
    $class = "info";

    switch ($status) {
        case 'confirmed':
        case 'completed':
            $class = 'success';
            break;
        case 'rejected':
        case 'canceled':
        case 'expired':
            $class = 'danger';
            break;
        case 'pending':
            $class = 'primary';
            break;
    }

    return $class;
}

/**
 * @param $file
 * @param $directory
 * @param $width
 * @return string
 * save resize image in storage
 */
function saveResizeImage($file, $directory, $width, $type = 'jpg')
{
    if (!Storage::exists($directory)) {
        Storage::makeDirectory("$directory");
    }
    $is_preview = strpos($directory, 'previews') !== false;
    $filename = Str::random() . time() . '.' . $type;
    $path = "$directory/$filename";
    $img = Image::make($file)->orientate()->encode($type, $is_preview ? 40 : 85)->resize($width, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    if ($width == $is_preview) {
        $img = $img->blur(60);
    }
    $resource = $img->stream()->detach();
    //add public
    Storage::disk('public')->put($path, $resource, 'public');
    return $path;
}

/**
 * @param $file
 * @param $directory
 * @return string
 * save file in storage
 */
function saveDocument($file, $directory)
{
    if (!Storage::exists($directory)) {
        Storage::makeDirectory("$directory");
    }
    $filename = Str::random() . time() . '.' . $file->getClientOriginalExtension();
    Storage::disk('public')->putFileAs($directory, $file, $filename);
    return $path = $directory . '/' . $filename;
}

/**
 * return image path;
 */
function getAvatar($imagePath)
{
    if($imagePath != 'default.png' && Storage::exists('public/users/images/avatar/' . $imagePath) && $imagePath != NULL){
        return asset('storage/users/images/avatar/' . $imagePath);
    } else {
        return asset('storage/__asset/img/default-avatar.png');
    }
}


/**
 * @param $file
 * get files
 */
function getFiles($file_name)
{
    $file = empty($file_name) ? '' : Storage::url($file_name);
    return empty($file) ? '' : $file;
}

/**
 * @param $file
 * delete a file
 */
function deleteFile($path)
{
    if (!empty($path) && file_exists('app/' . $path)) {
        unlink(storage_path('app/' . $path));
    }

    $storage_path = 'storage/' . $path;
    $public_path = public_path($storage_path);
    if (!empty($path) && file_exists($public_path)) {
        unlink($public_path);
    }
}

/**
 * return random colors;
 */
function randomIconColors($value)
{
    $mod = $value % 4;
    $colors = ['#4285F4', '#EC3566', '#56C5C7', '#F7934D'];
    return $colors[$mod];
}

/**
 * delete directory
 *
 * @param $directory
 */
function deleteDirectory($directory)
{
    if (Storage::exists($directory)) {
        Storage::deleteDirectory($directory);
    }
}

/**
 * convert time to 12 hours format
 *
 * @param $time
 * @return $time
 */
function convertTime($time, $format = 'g:i A')
{
    if ($time !== null) {
        return Carbon::parse($time)->format($format);
    }
}

function roundToNext10Min($dt, $precision = 10)
{
    $s = $precision * 60;
    $dt->setTimestamp($s * ceil($dt->getTimestamp() / $s));
    return $dt;
}

/**
 * convert time to 24 hours format
 *
 * @param $time
 * @return $time
 */
function convertTimeTo24($time)
{
    if ($time !== null) {
        return Carbon::parse($time)->format('H:i:s');
    }
}

/**
 * convert time
 *
 * @param $time
 * @return $time
 */
function generateAcronyms($name)
{
    $name = explode(' ', $name);
    return $name[0][0] . (array_key_exists(1, $name) ? $name[1][0] : "");
}

/**
 * Checks if the object is present in the given collection
 *
 * @param $permissions
 * @param $permission
 * @return bool
 */
function isChecked($permissions, $permission)
{
    if (count($permissions)) {
        // Collection, not db query
        return !!$permissions->where('name', $permission->name)->first();
    }
    return false;
}

/**
 * get events
 * @return string
 */
function getEvents()
{
    $events = Event::where('status', 'active')->get();
    return $events;
}

function getEventsForMenu()
{
    $events = Event::where('menu_status', 'active')->get();
    return $events;
}

function getCategories($limit = null, $offset = 0)
{
    $categories = Category::where(function ($query) {
        $query->where('visibility', '!=', 'hidden')
            ->orWhereNull('visibility');
    })->where('parent_id', 0)->whereStatus('1')->with('childs', function ($query) {
        $query->whereStatus(1);
    })->orderBy('sort', 'desc')->orderBy('name', 'asc')
        ->when(!empty($limit), function ($q) use ($limit) {
            $q->limit($limit);
        })
        ->when(!empty($offset), function ($q) use ($offset) {
            $q->offset($offset);
        })
        ->get()->sortBy(function ($category) {
            return $category->slug === "cashblack-to-your-door" ? 1 : 0;
        });

    return $categories;
}

function getStores($limit = null, $offset = 0)
{
    $categories = Category::where(function ($query) {
        $query->where('visibility', '!=', 'hidden')
            ->orWhereNull('visibility');
    })->where('parent_id', 0)->whereStatus('1')
        ->when(!empty($limit), function ($q) use ($limit) {
            $q->limit($limit);
        })
        ->when(!empty($offset), function ($q) use ($offset) {
            $q->offset($offset);
        })
        ->get();

    return $categories;
}


function SiteSetting()
{
    return SiteSetting::latest()->get()->pluck('value', 'type');
}

function getRecaptchaSiteKey()
{
    $key = SiteSetting::where('title', 'Site Key')->pluck('value')->first();
    return $key;
}

function getRecaptchaSecretKey()
{
    $key = SiteSetting::where('title', 'Secret')->pluck('value')->first();
    return $key;
}

function currency($number, $withSymbol = true)
{
    $settings = SiteSetting();
    $currencySymbol = '$';
    if (isset($settings['currency'])) {
        $currencySymbol = getCurrencySymbol();
    }
    $number = number_format((float)$number, 2, '.', '');
    return $withSymbol ? $currencySymbol . $number : $number;
}

function sidebarCategories()
{
    $sidebar_categories = Category::where(function ($query) {
        $query->where('visibility', '!=', 'hidden')
            ->orWhereNull('visibility');
    })->where('feature_sidebar', 1)->orderBy('name', 'ASC')->whereStatus('1')->get();
    return $sidebar_categories;
}

function sidebarStores()
{
    $stores = Store::whereHas('tags', function ($query) {
        $query->where('title', 'feature_sidebar');
    })->latest()->get();
    return $stores;
}

function textHighlight($text, $search, $highlightColor = '#3366cc', $casesensitive = false)
{
    return preg_replace('/(' . $search . ')/i', "<span class='color-primary'>$1</span>", $text);
}

function similarStores($store)
{
    $categorySlugs = $store->categories->pluck('slug')->toArray();

    if (in_array('cashblack-to-your-door', $categorySlugs)) {
        $ip = request()->ip();
        $data = Location::get($ip);
        $category = Category::whereStatus('1')->where(function ($query) {
            $query->where('visibility', '!=', 'hidden')
                ->orWhereNull('visibility');
        })->whereSlug('cashblack-to-your-door')->with('stores')->first();
        $allStores = $category->stores()->whereNotIn('store_id', [$store->id])->latest()->get();
        $allStores = sortByDistance($data, $allStores);
        $similarStores = $allStores->sortBy('distance')->values()->take(10);
    } else {
        $similarStores = Store::whereStatus('active')->whereHas('categories', function ($query) use ($categorySlugs) {
            return $query->whereIn('categories.slug', $categorySlugs);
        })->where('id', '!=', $store->id)
            ->limit(10)
            ->get();
    }
    return $similarStores;
}

function sortByDistance($data, $stores, $isSortBy = false)
{
    // Calculate distance between user and each store
    foreach ($stores as $store) {
        if (isset($store->storeAddress)) {
            $store->storeAddress = optional($store->storeAddress)->first();

            if (isset($store->storeAddress) && isset($data->longitude)) {
                $latitudeTo = $store->storeAddress->latitude;
                $longitudeTo = $store->storeAddress->longitude;

                $distance = calculateDistance($data->latitude, $data->longitude, $latitudeTo, $longitudeTo);

                $store->distance = number_format((float)$distance, 2, '.', '');
            } else {
                $store->distance = 'Unknown';
            }
        } else {
            $store['distance'] = 'Unknown';
        }
    }

    return $isSortBy ? $stores->sortBy('distance') : $stores;
}

function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $earthRadius = 6371; // km

    // Convert coordinates to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    // Calculate the differences
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    // Calculate the distance using the Haversine formula
    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    $distance = $angle * $earthRadius;

    return $distance;
}

function maintenance()
{
    if (file_exists(storage_path('framework/down'))) {
        return true;
    } else {
        return false;
    }
}

function isFacebookEnabled()
{
    if (isset(SiteSetting()['facebook_client_id']) && isset(SiteSetting()['facebook_client_secret']) && isset(SiteSetting()['facebook_url'])) {
        return true;
    } else {
        return false;
    }
}

function isGoogleEnabled()
{
    if (isset(SiteSetting()['google_client_id']) && isset(SiteSetting()['google_client_secret']) && isset(SiteSetting()['google_url'])) {
        return true;
    } else {
        return false;
    }
}

function checkSeoPageRule($url)
{
    $slug = request()->route('slug');
    if (!isset($slug)) {
        $path = parse_url($url, PHP_URL_PATH);
        preg_match('/[^\/]+$/', $path, $matches);
        $slug = isset($matches[0]) ? $matches[0] : '/';
    }
    $seoRules = SeoRule::where('is_enabled', 1)->with('ruleData')->where('url', $url)->first();
    if ($seoRules != null) {
        $metaDescription = [];
        $metaKeyword = [];
        $metaTitle = [];
        $title = Str::title(str_replace('-', ' ', $slug));
        foreach ($seoRules->ruleData as $rule) {
            $rule['key'] == 'meta_description' ?  $metaDescription[] = $rule['value'] : '';
            $rule['key'] == 'meta_keyword' ?  $metaKeyword[] = $rule['value'] : '';
            $rule['key'] == 'meta_title' ?  $metaTitle[] = $rule['value'] : '';
        }
        return  ['title' => $title, 'meta_title' => implode(',', $metaTitle), 'meta_description' => implode(',', $metaDescription), 'meta_keyword' => implode(',', $metaKeyword)];
    } elseif ($slug) {
        $routeNames = [
            'page' => '\App\Models\Page',
            'post' => '\App\Models\Blog',
            'appeal' => '\App\Models\Appeal',
            'store.location'  => '\App\Models\Category',
            'stores.show' => '\App\Models\Store',
        ];

        foreach ($routeNames as $model) {
            $record = $model::where('slug', $slug);
            if ($slug == '/' && $model == '\App\Models\Page') {
                $record = $model;
                if (empty(auth()->user())) {
                    $record = $record::where('slug', '/home-page-before-login');
                } else {
                    $record = $record::where('slug', '/home-page-after-login');
                }
            }
            $record = $record->first();
            if ($model == '\App\Models\Store' && isset($record)) {
                $seoRule = array();
                $seoRule['name']  = $record->name;
                $seoRule['meta_description'] = StoreSeoData::where('store_id', $record->id)->where('key', 'meta:description')->pluck('value')->first();
                $seoRule['meta_keyword'] = StoreSeoData::where('store_id', $record->id)->where('key', 'meta:keywords')->pluck('value')->first();
                $seoRule['meta_title'] = StoreSeoData::where('store_id', $record->id)->where('key', 'meta:title')->pluck('value')->first();
                return $seoRule;
            }
            if (isset($record) && (($record->title ? $record->title : $record->name) || $record->meta_description && $record->meta_keyword && $record->meta_title)) {
                return $record;
            }
        }
    }
    return null;
}

function getSocialSeo($seoRule, $url){
    $description = '';
    $title = '';
    $image = '';
    $extension = '';

    $slug = getSlug($url);
    $routeNames = [
        'page' => '\App\Models\Page',
        'post' => '\App\Models\Blog',
        'appeal' => '\App\Models\Appeal',
        'store.location'  => '\App\Models\Category',
        'stores.show' => '\App\Models\Store',
    ];
    foreach ($routeNames as $model) {
        $record = $model::where('slug', $slug);
        if ($slug == '/' && $model == '\App\Models\Page') {
            $record = $model;
            if (empty(auth()->user())) {
                $record = $record::where('slug', '/home-page-before-login');
            } else {
                $record = $record::where('slug', '/home-page-after-login');
            }
        }
        $record = $record->first();
        if($record != null){
            break;
        }
    }

    if (isset($seoRule['meta_title']) && !is_null($seoRule['meta_title'])) {
        $seoMetaTitle = $seoRule['meta_title'];
    }

    if (!isset($seoRule['meta_title']) && $slug == 'login') $title = 'Login';
    if (!isset($seoRule['meta_title']) && $slug == 'register') $title = 'Register';
    if (!isset($seoRule['meta_title']) && $slug == 'reset') $title = 'Forgot Password';

    if (!is_null($record)) {
        if($model == "\App\Models\Page"){
            $image = getImageUrl($record->banner_image);
            $pathInfo = $image != null ? pathinfo($image) : null;
            $extension = $pathInfo != null ? $pathInfo['extension'] : null;
            $description = $record->excerpt != null ? strip_tags($record->excerpt) : strip_tags($record->description);
            $title = $record->title;
        } else if ($model == "\App\Models\Blog"){
            $image = getImageUrl($record->featured_image);
            $pathInfo = $image != null ? pathinfo($image) : null;
            $extension = $pathInfo != null ? $pathInfo['extension'] : null;
            $description = $record->excerpt != null ? strip_tags($record->excerpt) : strip_tags($record->title);
            $title = $record->title;
        } else if ($model == "\App\Models\Appeal"){
            $image = $record->image_type == "upload" ? getImageUrl($record->image_upload) : getImageUrl($record->image_link);
            $pathInfo = $image != null ? pathinfo($image) : null;
            $extension = $pathInfo != null ? $pathInfo['extension'] : null;
            $description = $record->excerpt != null ? strip_tags($record->excerpt) : strip_tags($record->title);
            $title = $record->title;
        } else if ($model == "\App\Models\Category"){
            $image = $record->logo_type == "link" ? getImageUrl($record->logo_link) : getImageUrl($record->logo_upload);
            $pathInfo = $image != null ? pathinfo($image) : null;
            $extension = $pathInfo != null ? $pathInfo['extension'] : null;
            $description = $record->description != null?  strip_tags($record->description) : strip_tags($record->name);
            $title = $record->name;
        } else if ($model == "\App\Models\Store"){
            $image = getImageUrl(getImageUrl($record->images()->where('title', 'logo')->orWhere('title', 'large logo')->first()));
            $pathInfo = $image != null ? pathinfo($image) : null;
            $extension = $pathInfo != null ? $pathInfo['extension'] : null;
            $description = $record->description != null ? strip_tags($record->description) : strip_tags($record->name);
            $title = $record->name;
        }
    }

    if ($seoRule) {
        $socialSeoRule['description'] = $seoRule['meta_description'] != null ? $seoRule['meta_description'] : $description;
        $socialSeoRule['title'] = $seoMetaTitle ?? $title;
        $socialSeoRule['type'] = $slug == "/" ? "website" : "article";
        $socialSeoRule['url'] = $url;
        $socialSeoRule['published_time'] = isset($record) && !is_null($record) ? $record->created_at->format('Y-m-d H:i:s') : '';
        $socialSeoRule['modified_time'] = isset($record) && !is_null($record) ? $record->updated_at->format('Y-m-d H:i:s') : '';
        $socialSeoRule['image'] = !empty($image) ? $image : getSiteLogo();
        $socialSeoRule['width'] = "100%";
        $socialSeoRule["height"] = "auto";
        $socialSeoRule["image_type"] = "image/".$extension;
        return $socialSeoRule;
    }

    $socialSeoRule['description'] = $description;
    $socialSeoRule['title'] = $title;
    $socialSeoRule['type'] = $slug == "/" ? "website" : "article";
    $socialSeoRule['url'] = $url;
    $socialSeoRule['published_time'] = isset($record) && !is_null($record) ? $record->created_at->format('Y-m-d H:i:s') : '';
    $socialSeoRule['modified_time'] = isset($record) && !is_null($record) ? $record->updated_at->format('Y-m-d H:i:s') : '';
    $socialSeoRule['image'] = !empty($image) ? $image : getSiteLogo();
    $socialSeoRule['width'] = "100%";
    $socialSeoRule["height"] = "auto";
    $socialSeoRule["image_type"] = "image/".$extension;

    return $socialSeoRule;
}

function sendVerificationEmail($user)
{
    $verification_email_temp = EmailTemplate::where('key', 'email_verification')->first();

    $token = Str::random(64);

    UserVerify::create([
        'user_id' => $user->id,
        'token' => $token
    ]);

    $link = url('') . '/account/verify/' . $token;
    $button = '<a href="' . $link . '" target="_blank"><input type="button" class="btn btn-success" value="Verify"></a>';
    $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{BUTTON}}'], [SiteSetting()['website_title'], url('/'), $button], $verification_email_temp->message);
    $data = array(
        'email' => $user->email,
        'email_message' => $filtered_message,
        'subject' => $verification_email_temp->subject
    );
    Mail::send('emails.email_template', $data, function ($message) use ($data) {
        $message->to($data['email'])
            ->subject($data['subject']);
    });
}

function safeParseUrl($url)
{
    if (empty($url)) return null;

    $parsed = parse_url($url);

    if (array_key_exists('scheme', $parsed)) {
        return $parsed['scheme'] . '://' . $parsed['host'];
    }

    if (array_key_exists('path', $parsed)) {
        return 'http://' . $parsed['path'];
    }

    return null;
}

function convertPathForOS($path)
{
    if (empty($path)) return $path;

    return str_replace('\\', '/', str_replace('/', DIRECTORY_SEPARATOR, $path));
}

function getImageUrl($url)
{
    if (empty($url) || (isset($url->image) && empty($url->image))) return null;

    if (isset($url->image)) {
        $baseDir = $url->is_fake ? 'frontend/images/logos/' : '';

        return strpos($url->image, 'http') !== false
            ? (!$url->image ? asset('storage/__asset/img/no-logo.png') : $url->image)
            : asset($baseDir . ltrim($url->image, '/'));
    }

    return strpos($url, 'http') !== false
        ? $url
        : asset($url);
}

function getCategoryImageUrl($category)
{
    if ($category->logo_type == 'upload') {
        return !file_exists(public_path($category->logo_upload)) ? asset('storage/__asset/images/error-images/no-logo.png') : asset($category->logo_upload);
    }

    if ($category->logo_type == 'link')
        return $category->logo_link;

    return null;
}

/**
 * @param $file
 * get file
 */
function isFileExist($url)
{
    $file = '';
    if (!empty(parse_url($url)['path'])) {
        $file = file_exists(public_path(parse_url($url)['path']));
    }
    return $file;
}

/**
 * @param $date
 * Date format
 */
function dbDate($date)
{
    return Carbon::parse($date)->format('Y-m-d H:i:s');
}

function formatDateForUk($date)
{
    $parsedDate = DateTime::createFromFormat('d/m/Y', $date);
    return $parsedDate->format('Y-m-d H:i:s');
}

/**
 * @param $date
 * Date format in 'm/d/Y'
 */
function convertDateFormat($date)
{
    return Carbon::parse($date)->format('m/d/Y');
}

/**
 * Get banner image if not exist show default
 */
function getBannerImageUrl($url, $type = NULL, $row = null)
{
    $defaultBanner = asset('storage/__asset/img/brands/cashback.png');

    if (empty($url) || (!empty($url) && !isFileExist($url)) || ($row && $type && $row->banner_type != $type)) {
        return $defaultBanner;
    }

    return asset(parse_url($url)['path']);
}

/**
 * Get Logo image if not exist show default
 */
function getLogoImageUrl($url, $type = NULL, $row = null)
{
    $defaultLogo = asset('storage/__asset/img/brands/cashback.png');

    if (empty($url) || (!empty($url) && !isFileExist($url)) || ($row && $type && $row->logo_type != $type)) {
        return $defaultLogo;
    }

    return asset(parse_url($url)['path']);
}

function emailTemplate($key, $details, $filteredMessage = [], $requestFilteredMessage = [])
{
    $emailTemplate = EmailTemplate::where('key', $key)->first();

    $variables = ['{{SITE_TITLE}}', '{{SITE_URL}}', '{{NAME}}', '{{EMAIL}}', '{{SUBJECT}}', '{{MESSAGE}}'];
    $variablesMerge = array_merge($variables, $filteredMessage);

    $data = [SiteSetting()['website_title'], url('/'), $details['name'], $details['email'], $details['subject'], $details['message']];
    $dataMerge = array_merge($data, $requestFilteredMessage);

    if ($filteredMessage && $requestFilteredMessage) {
        $filteredAdminMessage  = str_replace($variablesMerge, $dataMerge, $emailTemplate->message);
    } else {
        $filteredAdminMessage  = str_replace($variables, $data, $emailTemplate->message);
    }

    $subject = str_replace(
        ['{{SUBJECT}}'],
        [$details['subject']],
        $emailTemplate->subject
    );

    return array(
        'message' => $filteredAdminMessage,
        'subject' => $subject
    );
}

function csvToArray($path)
{
    try {
        $header = null;
        $csvToArray = [];

        if (($handle = fopen(convertPathForOS(base_path($path)), 'r')) !== false) {
            while (($row = fgetcsv($handle, null, ',')) !== false) {
                if (!$header) {
                    $cleansedRow = [];
                    foreach ($row as $column) {
                        $cleansedRow[] = trim($column);
                    }

                    $header = $cleansedRow;
                } else {
                    $csvToArray[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }

        return $csvToArray;
    } catch (Exception $e) {
        return [];
    }
}

function getNewIndicatorClassForAdmin($type, $class = null)
{
    if ($type == 'reviews')
        $records = StoreReview::whereStatus('pending')->count();

    if ($type == 'sales')
        $records = Cashout::whereNewCashout(1)->count();

    if ($type == 'tickets')
        $records = Ticket::whereNewTicket(1)->count();

    if ($class != null)
        return $records ? 'icon-status-' . $class . ' icon-status-info-' . $class : '';

    return $records ? 'icon-status icon-status-info' : '';
}

function arrayValueExists($array, $key)
{
    return isset($array[$key]) && !empty($array[$key]);
}

function getMinimumCashoutAmount()
{
    $previousCashouts = auth()->user()->cashouts()->where('status', 'paid')->count();
    if (isset(SiteSetting()['min_cashout_amount']) && $previousCashouts == 0) {
        $min = SiteSetting()['min_cashout_amount'];
    } else if (isset(SiteSetting()['next_cashout_amount']) && $previousCashouts > 0) {
        $min = SiteSetting()['next_cashout_amount'];
    } else if ($previousCashouts == 0) {
        $min = 1;
    } else {
        $min = 2;
    }
    return $min;
}

function isWithdrawalAllowed()
{
    $cashoutStatuses = auth()->user()->cashouts()->pluck('status')->all();

    if (in_array('pending', $cashoutStatuses) || in_array('processing donation', $cashoutStatuses)) return "You cashout request is in process, you cannot withdraw";
    if (auth()->user()->availableBalance(3) < getMinimumCashoutAmount()) return "You have in sufficent balance in your account";
    return 2;
}

function getSiteLogo()
{
    $settings = SiteSetting();

    if (!isset($settings['website_logo'])) $siteLogo = asset('admin-dashboard/images/logo.png');
    else if ($settings['website_logo'] == 'default.png') $siteLogo = asset('admin-dashboard/images/logo.png');
    else if ($settings['website_logo'] == 'cashblack-default.png') $siteLogo = asset('cashblack/img/logo.png');
    else if ($settings['website_logo'] == 'logo.png') $siteLogo = asset('storage/__asset/images/logo/logo.png');
    else if (Storage::disk('public')->exists('dashboard/images/logo/' . $settings['website_logo']))
        $siteLogo = asset('storage/dashboard/images/logo/' . $settings['website_logo']);
    else
        $siteLogo = asset('admin-dashboard/images/logo.png');

    return $siteLogo;
}

function getDashboardLogo()
{
    $settings = SiteSetting();
    $siteLogo = (empty($settings['dashboard_logo']) ? asset('admin-dashboard/images/logo-dark.png') : ($settings['dashboard_logo'] == 'default.png' ? asset('admin-dashboard/images/logo-dark.png') : ($settings['dashboard_logo'] == 'cashblack-default.png'
        ? asset('storage/__asset/img/logo.png') : asset('storage/dashboard/images/logo/' . $settings['dashboard_logo']))));
    return $siteLogo;
}

function getRandomColorClass()
{
    $color = rand(1, 5);
    if ($color == 1) return 'bg-info';
    if ($color == 2) return 'bg-primary';
    if ($color == 3) return 'bg-danger';
    if ($color == 4) return 'bg-success';
    if ($color == 5) return 'bg-warning';
    return null;
}

function currencyOrPercentage($number, $type = 'fixed', $symbol = null)
{
    if ($type == 'fixed') {
        return !empty($symbol) ? $symbol . currency($number, false) : currency($number);
    } else {
        return currency($number, false) . '%';
    }
}

function getCurrencySymbol($symbol = null)
{
    $settings = SiteSetting();
    $currencySymbol = '$'; // Set a default value

    if (isset($settings['currency'])) {
        $currencySymbol = !empty($symbol) ? $symbol : Currency::where('id', $settings['currency'])->pluck('symbol')->first();
    }

    return $currencySymbol;
}

function getSiteFavicon()
{
    $settings = SiteSetting();
    if (!isset($settings['favicon'])) $siteFavicon = asset('admin-dashboard/images/favicon.png');
    else if ($settings['favicon'] == 'default.png') $siteFavicon = asset('admin-dashboard/images/favicon.png');
    else if ($settings['favicon'] == 'cashblack-default.png') $siteFavicon = asset('storage/__asset/img/favicon.png');
    else if ($settings['favicon'] == 'favicon.ico' || $settings['favicon'] == 'favicon.png') $siteFavicon = asset('storage/__asset/images/logo/' . $settings['favicon']);
    else if (Storage::disk('public')->exists('dashboard/images/logo/' . $settings['favicon']))
        $siteFavicon = asset('storage/dashboard/images/logo/' . $settings['favicon']);
    else
        $siteFavicon = asset('admin-dashboard/images/favicon.png');

    return $siteFavicon;
}

function resolvePageShortCodes($content, $data = [])
{
    preg_match_all('/\[\S[a-zA-Z0-9-]*\]/', $content, $shortCodes);

    if (empty($shortCodes) || empty($shortCodes[0])) return $content;

    foreach ($shortCodes[0] as $shortCode) {
        $viewName = 'frontend.templates.' . preg_replace('/[\[\]]/', '', $shortCode);

        if (view()->exists($viewName)) {
            $content = str_replace($shortCode, view($viewName, $data)->render(), $content);
        }
    }

    return $content;
}
function getFaqsContent()
{
    $page = Page::where('slug', 'faqs')->first();
    if (!$page) {
        return '';
    }
    $content = $page->lb_raw_content;
    $content = preg_replace('/\[(.*?)\]/', '', $content);
    return $content;
}

function retrieveNotification($offset)
{
    $take = $offset + 10;
    $notifications = auth()->user()->notifications()->whereNull('read_at')->latest()->take($take)->get();
    return $notifications;
}

function sendEmailNotification(Ticket $ticket)
{
    $userEmailTemplateKey = 'user_new_ticket';
    $adminEmailTemplateKey = 'admin_new_ticket';
    $filterMessageVariables = ['{{TICKET_ID}}', '{{TICKETTYPE}}'];
    $requestFilteredMessage = [$ticket->ticket_id, $ticket->claim_type];

    $subject = ['subject' => null];
    $data = [
        'name' => $ticket->user->first_name . ' ' . $ticket->user->last_name,
        'email' => $ticket->user->email,
        'message' => $ticket->message,
    ];
    $data = array_merge($data, $subject);

    SendEmailToUser::dispatch($userEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
    SendEmailToAdmin::dispatch($adminEmailTemplateKey, $data, $filterMessageVariables, $requestFilteredMessage);
}

function formatDateTimezone($date, $format){
    return Carbon::parse($date)->timezone(env('TIMEZONE', 'UTC'))->isoFormat($format);
}

function uniqueRefLinkGenerator()
{
    while (true) {
        $refId = Str::random(10);
        $existingUser = User::where('short_ref_id', $refId)->first();
        if (empty($existingUser)) break;
    }

    if (!auth()->check()) return $refId;

    //This will add short_ref_id for all the users registered with the system when they login.
    auth()->user()->update(['short_ref_id' => $refId]);
}

function getPageRoute($type, $slug){
    if($type == "system"){
        if($slug == "/home-page-before-login" || $slug == "/home-page-after-login"){
            return url('/');
        } else{
            return url($slug);
        }
    } else if($type == "general" || $type == "special"){
        return route('pages.show', $slug);
    }
}

function getSlug($url){
    $slug = request()->route('slug');
    if (!isset($slug)) {
        $path = parse_url($url, PHP_URL_PATH);
        preg_match('/[^\/]+$/', $path, $matches);
        $slug = isset($matches[0]) ? $matches[0] : '/';
    }
    return $slug;
}

function setStoreDefaultCashback($storeId, $isDefault = true, $isDeleted = false)
{
    $cashbacks = StoreCashback::where('store_id', $storeId);
    $clone = $cashbacks->clone();

    if ($isDefault) {
        foreach($cashbacks->withTrashed()->get() as $cashback)
            $cashback->update(['default' => false]);

        if ($isDeleted == true && $clone->count() > 0)
            $clone->orderByDesc('sale_commission')->first()->update(['default' => true]);
    } else {
        if (
            ($clone->withTrashed()->whereDefault(0)->count() > 0 || $clone->withTrashed()->whereDefault(1)->count() > 0) &&
            $clone->count() != 0 && $clone->whereDefault(1)->count() == 0 && StoreCashback::where('store_id', $storeId)->whereDefault(1)->count() != 1
            )
        {
            foreach($cashbacks->withTrashed()->get() as $cashback)
                $cashback->update(['default' => false]);

            // Make highest cashback default
            $highestCashback = $cashbacks->orderByDesc('sale_commission')->first();
            $highestCashback->update(['default' => true]);
        } else if ($cashbacks->count() == 1) {
            $cashbacks->first()->update(['default' => true]);
        }
    }
}

function singleFeaturedStore($category)
{
    $editorPickStore = $category->picks()->orderBy('id', 'desc')->first();

    if ($editorPickStore)
        return $editorPickStore->store()->where('status', 'active')->withCount('cashbacks')->first();

    return null;
}
