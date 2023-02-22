<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Ticket;
use App\Models\Cashout;
use App\Models\SeoRule;
use App\Models\Category;
use App\Models\Currency;
use App\Models\UserVerify;
use App\Models\SiteSetting;
use App\Models\StoreReview;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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

function store_user_avatar($file, $existing_file)
{
    if ($existing_file != "default.png") {
        if (File::exists(public_path('storage/users/images/avatar/' . $existing_file))) {
            File::delete(public_path('storage/users/images/avatar/' . $existing_file));
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
    $image = \File::get($dir . '/' . $filename);
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
        if ($format)
            return Carbon::parse($date)->format('d M, Y');

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
function getImage($image, $isAvatar = false)
{
    $errorImage = $isAvatar ? url('/images/no_avatar.jpg') : url('/images/no_image.png');
    return !empty($image) && Storage::disk('public')->exists($image)
        ? Storage::url($image) : $errorImage;
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
    return $intials = $name[0][0] . (array_key_exists(1, $name) ? $name[1][0] : "");
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
    $categories = Category::where('parent_id', 0)
        ->when(!empty($limit), function ($q) use ($limit) {
            $q->limit($limit);
        })
        ->when(!empty($offset), function ($q) use ($offset) {
            $q->offset($offset);
        })
        ->get();

    return $categories;
}

function getStores($limit = null, $offset = 0)
{
    $categories = Category::where('parent_id', 0)
        ->when(!empty($limit), function ($q) use ($limit) {
            $q->limit($limit);
        })
        ->when(!empty($offset), function ($q) use ($offset) {
            $q->offset($offset);
        })
        ->get();

    return $categories;
}

function getPaginatedStores($perPage = 12, $letter = null)
{
    return Store::when(!empty($letter), function ($q) use ($letter) {
        $q->where('name', 'like', $letter . '%');
    })->orderBy('name', 'asc')->paginate($perPage);
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

function currency()
{
    $settings = SiteSetting();
    $currency = Currency::where('id', $settings['currency'])->pluck('symbol')->first();
    return $currency;
}

function sidebarCategories()
{
    $sidebar_categories = Category::where('feature_sidebar', 1)->orderBy('name', 'ASC')->get();
    return $sidebar_categories;
}

function sidebarStores()
{
    $stores = Store::where('feature_sidebar', 1)->latest()->get();
    return $stores;
}
function textHighlight($text, $search, $highlightColor = '#3366cc', $casesensitive = false)
{
    return preg_replace('/(' . $search . ')/i', "<span class='color-primary'>$1</span>", $text);
}

function similarStores($store)
{
    $categoryIds = $store->categories->pluck('id')->toArray();

    $similarStores = Store::whereHas('categories', function ($query) use ($categoryIds) {
        return $query->whereIn('categories.id', $categoryIds);
    })->where('id', '!=', $store->id)
        ->limit(10)
        ->get();
    return $similarStores;
}

function maintenance()
{
    if (file_exists(storage_path('framework/down'))) {
        return true;
    } else
        return false;
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

function checkStaticpageRule($url)
{
    $slug = request()->route('slug');
    $current_route_name = Request::route()->getName();
    $seo_rules = SeoRule::where('is_enabled', 1)->with('ruleData')->where('url', $url)->first();
    if ($seo_rules != null) {
        $meta_description = [];
        $meta_keyword = [];
        $title = Str::title(str_replace('-', ' ', $slug));
        foreach ($seo_rules->ruleData as $rule) {
            $rule['key'] == 'meta_description' ?  $meta_description[] = $rule['value'] : '';
            $rule['key'] == 'meta_keyword' ?  $meta_keyword[] = $rule['value'] : '';
        }
        return  ['title' => $title, 'meta_description' => implode(',', $meta_description), 'meta_keyword' => implode(',', $meta_keyword)];
    } elseif ($slug) {
        $route_names = [
            'post' => '\App\Models\Blog',
            'page' => '\App\Models\Page',
            'store.location'  => '\App\Models\Category',
            'store.show' => '\App\Models\Store'
        ];

        foreach ($route_names as $route_name => $model) {
            if ($current_route_name == 'store.show') {
                $meta_description = [];
                $meta_keyword = [];
                $store = Store::where('slug', $slug)->select('id', 'name')->with('storeRuleData')->first();
                $title = $store->name;
                foreach ($store->storeRuleData as $meta_data) {
                    $meta_data['key'] == 'meta:description' ? $meta_description[] = $meta_data['value'] : '';
                    $meta_data['key'] == 'meta:keywords' ? $meta_keyword[] = $meta_data['value'] : '';
                }
                return ['title' => $title, 'meta_description' => implode(',', $meta_description), 'meta_keyword' => implode(',', $meta_keyword)];
            } elseif ($route_name == $current_route_name) {
                $record = $model::where('slug', $slug)->first();
                if (($record->title ? $record->title : $record->name) || $record->meta_description && $record->meta_keyword) {
                    return $record;
                }
                return null;
            }
        }
    } else {
        return null;
    }
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
    if (empty($url) || (isset($url->image) && empty($url->image))) {
        return asset('frontend/images/products/product-16.jpg');
    }

    if (isset($url->image)) {
        $baseDir = $url->is_fake ? 'frontend/images/logos/' : 'storage/stores/images/';

        return strpos($url->image, 'http') !== false
            ? $url->image
            : asset($baseDir . ltrim($url->image, '/'));
    }

    return strpos($url, 'http') !== false
        ? $url
        : asset('storage/stores/images/' . ltrim($url, '/'));
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
    $defaultBanner = asset('frontend/images/banners/categories/cashback.png');

    if (empty($url) || (!empty($url) && !isFileExist($url)) || ($row && $type && $row->banner_type != $type)) {
        return $defaultBanner;
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
    return arrayValueExists(SiteSetting(), 'min_cashout_amount') ? SiteSetting()['min_cashout_amount'] : 1;
}

function isWithdrawalAllowed()
{
    $cashoutStatuses = auth()->user()->cashouts()->pluck('status')->all();

    return auth()->user()->availableBalance() >= getMinimumCashoutAmount() && !in_array('pending', $cashoutStatuses) && !in_array('processing donation', $cashoutStatuses);
}
