<?php

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Category;
// use Intervention\Image\Image;
use Harimayco\Menu\Models\Menus;
use Harimayco\Menu\Models\MenuItems;
use App\Models\UserVerify;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

// function ccEmails() {
//     return config('mail.toSend.ccs');
// }

// function bccEmails() {
//     return config('mail.toSend.bcc');
// }

// function allEmails() {
//     return config('mail.toSend.all');
// }

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
function convertDate($date, $format=true)
{
    if ($date !== null) {
        if($format)
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
    return $path = $directory.'/'.$filename;
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

function getCategories()
{
    $categories = Category::where('parent_id',0)->orderBy('name', 'ASC')->get();
    return $categories;
}

function SiteSetting(){
    return \App\Models\SiteSetting::latest()->get()->pluck('value','type');
}
function currency(){
    $settings = SiteSetting();
$currency = \App\Models\Currency::where('id',$settings['currency'])->pluck('symbol')->first();
return $currency;

}

// config()->set('settings',$settings);
// $currency = \App\Models\Currency::where('id',$settings['currency'])->pluck('symbol')->first();
// config()->set('currency',$currency);

function sidebarCategories(){
    $sidebar_categories = Category::where('feature_sidebar',1)->orderBy('name', 'ASC')->get();
    return $sidebar_categories;
}

function sidebarStores(){
    $stores = Store::where('feature_sidebar',1)->latest()->get();
    return $stores;
}
function textHighlight($text,$search,$highlightColor='#3366cc',$casesensitive=false)
{
    return preg_replace('/(' . $search . ')/i', "<span class='color-primary'>$1</span>", $text);
}

function similarStores($store){
    $categoryIds = $store->categories->pluck('id')->toArray();

    $similarStores = Store::whereHas('categories', function ($query) use ($categoryIds) {
        return $query->whereIn('categories.id', $categoryIds);
    })->where('id','!=', $store->id)
        ->limit(10)
        ->get();
    return $similarStores;
}


function maintenance(){
    if (file_exists(storage_path('framework/down'))) {
        return true;
    }
    else
     return false;
}

function isFacebookEnabled(){
    if(isset(SiteSetting()['facebook_client_id']) && isset(SiteSetting()['facebook_client_secret']) && isset(SiteSetting()['facebook_url'])){
        return true;
    }else{
        return false;
    }
}

function isGoogleEnabled(){
    if(isset(SiteSetting()['google_client_id']) && isset(SiteSetting()['google_client_secret']) && isset(SiteSetting()['google_url'])){
        return true;
    }else{
        return false;
    }
}

function metaKeyword($keywords)
{
    return implode( ',' , $keywords->where('type', 'meta')->where('key', 'meta_keyword')->pluck('value')->toArray());
}

function metaDescription($descriptions)
{
    return implode( ',' , $descriptions->where('type', 'meta')->where('key', 'meta_description')->pluck('value')->toArray());
}

function checkStaticpageRule($url)
{
    $store_rules = App\Models\Store_seo_data::where('url',$url)->get();

    $blog = App\Models\Blog::where('url',$url)->first();

    $categories = App\Models\Category::where('url',$url)->first();
   if($store_rules != null)
   {
        $meta_description = [];
        $meta_keyword = [];
        foreach($store_rules as $rule)
        {
            $rule['key'] == 'meta:description'?  $meta_description[] =$rule['value'] : '';
            $rule['key'] == 'meta:keywords'?  $meta_keyword[] =$rule['value'] : '';
        }
        return  ['meta_description' =>implode( ',' , $meta_description ) , 'meta_keyword'=>implode( ',' , $meta_keyword ) ];

   }elseif(!empty($blog)){

    return $blog;

   }elseif(!empty($categories)){

    return $categories;

   }else{

    return null;

   }
}

   function sendVerificationEmail($user)
   {
    $verification_email_temp = EmailTemplate::where('key','email_verification')->first();

    $token = Str::random(64);

    UserVerify::create([
          'user_id' => $user->id, 
          'token' => $token
        ]);

    $link = url('').'/account/verify/'.$token;
    $button = '<a href="'.$link.'" target="_blank"><input type="button" class="btn btn-success" value="Verify"></a>';
    $filtered_message  = str_replace(['{{SITE_TITLE}}', '{{SITE_URL}}', '{{BUTTON}}'],[SiteSetting()['website_title'], url('/'), $button],$verification_email_temp->message );
    $data = array(
        'email'=> $user->email,
        'email_message'=>$filtered_message,
        'subject'=>$verification_email_temp->subject
    );
    Mail::send('emails.email_template', $data, function ($message) use ($data) {
        $message->to($data['email'])
            ->subject($data['subject']);
    });
   }

// function isAppleEnabled(){
//     if(SiteSetting()['apple_client_id'] && SiteSetting()['apple_client_secret'] && SiteSetting()['apple_url']){
//         return true;
//     }else{
//         return false;
//     }
// }

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
