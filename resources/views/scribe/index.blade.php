<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Cashback Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var baseUrl = "http://127.0.0.1:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-3.37.2.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-3.37.2.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image" />
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                                                                            <ul id="tocify-header-0" class="tocify-header">
                    <li class="tocify-item level-1" data-unique="introduction">
                        <a href="#introduction">Introduction</a>
                    </li>
                                            
                                                                    </ul>
                                                <ul id="tocify-header-1" class="tocify-header">
                    <li class="tocify-item level-1" data-unique="authenticating-requests">
                        <a href="#authenticating-requests">Authenticating requests</a>
                    </li>
                                            
                                                </ul>
                    
                    <ul id="tocify-header-2" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-register">
                        <a href="#endpoints-POSTapi-auth-register">POST api/auth/register</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-social">
                        <a href="#endpoints-POSTapi-auth-social">POST api/auth/social</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auth-login">
                        <a href="#endpoints-POSTapi-auth-login">POST api/auth/login</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-password-email">
                        <a href="#endpoints-POSTapi-password-email">POST api/password/email</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user-user-balance">
                        <a href="#endpoints-GETapi-user-user-balance">GET api/user/user-balance</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-change_password">
                        <a href="#endpoints-POSTapi-change_password">POST api/change_password</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-stores">
                        <a href="#endpoints-GETapi-stores">Display a listing of the resource.</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-stores--id-">
                        <a href="#endpoints-GETapi-stores--id-">GET api/stores/{id}</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-vouchers">
                        <a href="#endpoints-GETapi-vouchers">GET api/vouchers</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-verify_otp">
                        <a href="#endpoints-POSTapi-verify_otp">POST api/verify_otp</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-resend_otp">
                        <a href="#endpoints-POSTapi-resend_otp">POST api/resend_otp</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-main_search">
                        <a href="#endpoints-POSTapi-main_search">POST api/main_search</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-auth-logout">
                        <a href="#endpoints-GETapi-auth-logout">GET api/auth/logout</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-update_profile">
                        <a href="#endpoints-POSTapi-update_profile">POST api/update_profile</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-update_avatar">
                        <a href="#endpoints-POSTapi-update_avatar">POST api/update_avatar</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user_data">
                        <a href="#endpoints-GETapi-user_data">GET api/user_data</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user_bank_details">
                        <a href="#endpoints-POSTapi-user_bank_details">POST api/user_bank_details</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-cashback">
                        <a href="#endpoints-POSTapi-user-cashback">POST api/user/cashback</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-clicks">
                        <a href="#endpoints-POSTapi-user-clicks">POST api/user/clicks</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-tickets">
                        <a href="#endpoints-POSTapi-user-tickets">POST api/user/tickets</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-referral-data">
                        <a href="#endpoints-POSTapi-user-referral-data">POST api/user/referral-data</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-refer_and_earn">
                        <a href="#endpoints-GETapi-refer_and_earn">GET api/refer_and_earn</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-send_refer_email">
                        <a href="#endpoints-POSTapi-send_refer_email">POST api/send_refer_email</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-user-cashouts">
                        <a href="#endpoints-POSTapi-user-cashouts">POST api/user/cashouts</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-create_ticket">
                        <a href="#endpoints-POSTapi-create_ticket">POST api/create_ticket</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-ticket_stores">
                        <a href="#endpoints-GETapi-ticket_stores">GET api/ticket_stores</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-ticket_clicks">
                        <a href="#endpoints-POSTapi-ticket_clicks">POST api/ticket_clicks</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-get-fav-stores">
                        <a href="#endpoints-POSTapi-get-fav-stores">POST api/get-fav-stores</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-get-fav-cbdoor">
                        <a href="#endpoints-POSTapi-get-fav-cbdoor">POST api/get-fav-cbdoor</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-add-fav-store">
                        <a href="#endpoints-POSTapi-add-fav-store">Display the specified resource.</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-remove-fav-store">
                        <a href="#endpoints-POSTapi-remove-fav-store">POST api/remove-fav-store</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-dashboard_data">
                        <a href="#endpoints-POSTapi-dashboard_data">Display a listing of the resource.</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-charities">
                        <a href="#endpoints-POSTapi-charities">POST api/charities</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-withdraw">
                        <a href="#endpoints-POSTapi-withdraw">POST api/withdraw</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-charity_withdraw">
                        <a href="#endpoints-POSTapi-charity_withdraw">POST api/charity_withdraw</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-charity_user_cashbacks_and_types">
                        <a href="#endpoints-GETapi-charity_user_cashbacks_and_types">GET api/charity_user_cashbacks_and_types</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-enable_notifications">
                        <a href="#endpoints-POSTapi-enable_notifications">POST api/enable_notifications</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-disable_notifications">
                        <a href="#endpoints-GETapi-disable_notifications">GET api/disable_notifications</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-afrobot">
                        <a href="#endpoints-POSTapi-afrobot">POST api/afrobot</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-competitor-stores">
                        <a href="#endpoints-POSTapi-competitor-stores">POST api/competitor-stores</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-store-detail--id-">
                        <a href="#endpoints-GETapi-store-detail--id-">GET api/store-detail/{id}</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-home">
                        <a href="#endpoints-GETapi-home">GET api/home</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-categories--letter--">
                        <a href="#endpoints-GETapi-categories--letter--">GET api/categories/{letter?}</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-child-categories--slug---letter--">
                        <a href="#endpoints-GETapi-child-categories--slug---letter--">GET api/child-categories/{slug}/{letter?}</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-get-category-stores--slug-">
                        <a href="#endpoints-GETapi-get-category-stores--slug-">GET api/get-category-stores/{slug}</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="endpoints-POSTapi-exit-click">
                        <a href="#endpoints-POSTapi-exit-click">POST api/exit-click</a>
                    </li>
                                                    </ul>
                            </ul>
        
                        
            </div>

            <ul class="toc-footer" id="toc-footer">
                            <li><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                            <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
                    </ul>
        <ul class="toc-footer" id="last-updated">
        <li>Last updated: August 16 2023</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>This documentation aims to provide all the information you need to work with our API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
<blockquote>
<p>Base URL</p>
</blockquote>
<pre><code class="language-yaml">http://127.0.0.1:8000</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

            <h2 id="endpoints-POSTapi-auth-register">POST api/auth/register</h2>

<p>
</p>



<span id="example-requests-POSTapi-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-register">
</span>
<span id="execution-results-POSTapi-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-register"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-register"></code></pre>
</span>
<form id="form-POSTapi-auth-register" data-method="POST"
      data-path="api/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-register"
                    onclick="tryItOut('POSTapi-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-register"
                    onclick="cancelTryOut('POSTapi-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-register" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/register</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-auth-social">POST api/auth/social</h2>

<p>
</p>



<span id="example-requests-POSTapi-auth-social">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/auth/social" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"firstname\": \"rvrhewjtfhysjshbyjnrmolstudibpxmzabvavrdzxjufppptypvhrrzulqymnquixtggeckxkgfurbcpqihrolyohm\",
    \"lastname\": \"putbjlinsyqwtoonrjaippbxokzpcxdhnbyxneuqjyeqohrsglsktzkfhlubozlbyfiowcjjfzobpnibmxxdydqkzidggbyfzombvzguhblmvskbhnwwwfomhmugetbsmshorfgfgrgshvdbcnmmpqwspnyozogzosshofdnhghfoqrecnwevjlfmdlfqbykmnumprnnysyxkkdyvvedtodluhnycurqn\",
    \"email\": \"tokqsguidhsyomamnrdngmnaukmbhqrlihlrnnbgbzbfwkldzbbtrxmncoiczsukokrknlnkbhndwtfmubagnmzwdpmbtgwk\",
    \"provider\": \"totam\",
    \"provider_id\": \"corporis\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/auth/social"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "firstname": "rvrhewjtfhysjshbyjnrmolstudibpxmzabvavrdzxjufppptypvhrrzulqymnquixtggeckxkgfurbcpqihrolyohm",
    "lastname": "putbjlinsyqwtoonrjaippbxokzpcxdhnbyxneuqjyeqohrsglsktzkfhlubozlbyfiowcjjfzobpnibmxxdydqkzidggbyfzombvzguhblmvskbhnwwwfomhmugetbsmshorfgfgrgshvdbcnmmpqwspnyozogzosshofdnhghfoqrecnwevjlfmdlfqbykmnumprnnysyxkkdyvvedtodluhnycurqn",
    "email": "tokqsguidhsyomamnrdngmnaukmbhqrlihlrnnbgbzbfwkldzbbtrxmncoiczsukokrknlnkbhndwtfmubagnmzwdpmbtgwk",
    "provider": "totam",
    "provider_id": "corporis"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-social">
</span>
<span id="execution-results-POSTapi-auth-social" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-social"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-social"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-social" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-social"></code></pre>
</span>
<form id="form-POSTapi-auth-social" data-method="POST"
      data-path="api/auth/social"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-social', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-social"
                    onclick="tryItOut('POSTapi-auth-social');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-social"
                    onclick="cancelTryOut('POSTapi-auth-social');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-social" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/social</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>firstname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="firstname"
               data-endpoint="POSTapi-auth-social"
               value="rvrhewjtfhysjshbyjnrmolstudibpxmzabvavrdzxjufppptypvhrrzulqymnquixtggeckxkgfurbcpqihrolyohm"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>lastname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="lastname"
               data-endpoint="POSTapi-auth-social"
               value="putbjlinsyqwtoonrjaippbxokzpcxdhnbyxneuqjyeqohrsglsktzkfhlubozlbyfiowcjjfzobpnibmxxdydqkzidggbyfzombvzguhblmvskbhnwwwfomhmugetbsmshorfgfgrgshvdbcnmmpqwspnyozogzosshofdnhghfoqrecnwevjlfmdlfqbykmnumprnnysyxkkdyvvedtodluhnycurqn"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="email"
               data-endpoint="POSTapi-auth-social"
               value="tokqsguidhsyomamnrdngmnaukmbhqrlihlrnnbgbzbfwkldzbbtrxmncoiczsukokrknlnkbhndwtfmubagnmzwdpmbtgwk"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>provider</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="provider"
               data-endpoint="POSTapi-auth-social"
               value="totam"
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>provider_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="provider_id"
               data-endpoint="POSTapi-auth-social"
               value="corporis"
               data-component="body" hidden>
    <br>

        </p>
        </form>

            <h2 id="endpoints-POSTapi-auth-login">POST api/auth/login</h2>

<p>
</p>



<span id="example-requests-POSTapi-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-login">
</span>
<span id="execution-results-POSTapi-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-login"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-login"></code></pre>
</span>
<form id="form-POSTapi-auth-login" data-method="POST"
      data-path="api/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-login"
                    onclick="tryItOut('POSTapi-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-login"
                    onclick="cancelTryOut('POSTapi-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-login" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/login</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-password-email">POST api/password/email</h2>

<p>
</p>



<span id="example-requests-POSTapi-password-email">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/password/email" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"aurelie.funk@example.net\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/password/email"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "aurelie.funk@example.net"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-password-email">
</span>
<span id="execution-results-POSTapi-password-email" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-password-email"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-password-email"></code></pre>
</span>
<span id="execution-error-POSTapi-password-email" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-password-email"></code></pre>
</span>
<form id="form-POSTapi-password-email" data-method="POST"
      data-path="api/password/email"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-password-email', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-password-email"
                    onclick="tryItOut('POSTapi-password-email');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-password-email"
                    onclick="cancelTryOut('POSTapi-password-email');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-password-email" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/password/email</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="email"
               data-endpoint="POSTapi-password-email"
               value="aurelie.funk@example.net"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address.</p>
        </p>
        </form>

            <h2 id="endpoints-GETapi-user-user-balance">GET api/user/user-balance</h2>

<p>
</p>



<span id="example-requests-GETapi-user-user-balance">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/user/user-balance" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/user-balance"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user-user-balance">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user-user-balance" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user-user-balance"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-user-balance"></code></pre>
</span>
<span id="execution-error-GETapi-user-user-balance" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-user-balance"></code></pre>
</span>
<form id="form-GETapi-user-user-balance" data-method="GET"
      data-path="api/user/user-balance"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user-user-balance', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user-user-balance"
                    onclick="tryItOut('GETapi-user-user-balance');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user-user-balance"
                    onclick="cancelTryOut('GETapi-user-user-balance');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user-user-balance" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user/user-balance</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-change_password">POST api/change_password</h2>

<p>
</p>



<span id="example-requests-POSTapi-change_password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/change_password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"old_password\": \"esse\",
    \"new_password\": \"officiis\",
    \"confirm_password\": \"corrupti\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/change_password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "old_password": "esse",
    "new_password": "officiis",
    "confirm_password": "corrupti"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-change_password">
</span>
<span id="execution-results-POSTapi-change_password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-change_password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-change_password"></code></pre>
</span>
<span id="execution-error-POSTapi-change_password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-change_password"></code></pre>
</span>
<form id="form-POSTapi-change_password" data-method="POST"
      data-path="api/change_password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-change_password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-change_password"
                    onclick="tryItOut('POSTapi-change_password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-change_password"
                    onclick="cancelTryOut('POSTapi-change_password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-change_password" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/change_password</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>old_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="old_password"
               data-endpoint="POSTapi-change_password"
               value="esse"
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>new_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="new_password"
               data-endpoint="POSTapi-change_password"
               value="officiis"
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>confirm_password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="confirm_password"
               data-endpoint="POSTapi-change_password"
               value="corrupti"
               data-component="body" hidden>
    <br>
<p>The value and <code>new_password</code> must match.</p>
        </p>
        </form>

            <h2 id="endpoints-GETapi-stores">Display a listing of the resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-stores">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/stores" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/stores"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-stores">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;main_banner_image&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
        &quot;stores&quot;: [
            {
                &quot;id&quot;: 3684,
                &quot;title&quot;: &quot;100% PURE&quot;,
                &quot;url_key&quot;: &quot;100-pure&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/W6cIx4OrVGysCzEd_PURE160-logo.png&quot;,
                &quot;description&quot;: &quot;100% PURE is a commitment to producing the purest, healthiest products and educating everyone on why being 100% PURE is so important. We strive to live with compassion, kindness and empathy; to be environmentally sustainable and to improve the lives of 6 billion people and animals.&quot;,
                &quot;cashback&quot;: &quot;2.70% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 4024,
                &quot;title&quot;: &quot;11 Degrees&quot;,
                &quot;url_key&quot;: &quot;11-degrees&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/thnmsjwMM1LYVa07_11-degree-160x140-logo-logo.png&quot;,
                &quot;description&quot;: &quot;11 Degrees. Started in 2014, the range consists of urban-ready T-shirts, hoodies, tracksuits, and jackets. Here at 11 Degrees, we use higher quality materials and production techniques because we're all about comfort. For us, comfort is not about being lazy...far from it.&quot;,
                &quot;cashback&quot;: &quot;1.35% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 3586,
                &quot;title&quot;: &quot;12 Storeez&quot;,
                &quot;url_key&quot;: &quot;12-storeez&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/vtXz8XjztKmH3m75_12st-logo.png&quot;,
                &quot;description&quot;: &quot;We celebrate versatile designs, feminine chic and premium-quality materials. We want to save you time and effort, and provide you with a wardrobe you will wear for years, and even decades, to come. We believe in designs that can be easily matched with the rest of your wardrobe.&quot;,
                &quot;cashback&quot;: &quot;2.70% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 2100,
                &quot;title&quot;: &quot;121Doc.co.uk&quot;,
                &quot;url_key&quot;: &quot;121doccouk&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/NrEBF1KYzyqf4rI1_121-logo.gif&quot;,
                &quot;description&quot;: &quot;Since 2004, 121Doc have been providing private patients with access to qualified doctors and pharmacies for medical conditions such as male impotence, obesity, premature ejaculation, influenza, Genital Herpes, Emergency Contraception, Female Sexual Dysfunction, smoking and male hair loss. 121Doc are an established online clinic based in London, England and provide services to UK and EU patients.&quot;,
                &quot;cashback&quot;: &quot;1.35% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 3789,
                &quot;title&quot;: &quot;123 Casinos&quot;,
                &quot;url_key&quot;: &quot;123-casinos&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/1jagDFvDzE30FNZ2_123-casino-160x140-logo-logo.png&quot;,
                &quot;description&quot;: &quot;Welcome to 123 Casinos, after extensive research our team of experts have scoured the web to bring to you the best free spins offers available to the public. We ensure to have the best free spins offers, updating our databases daily to find the best brands to claim your free spins!&quot;,
                &quot;cashback&quot;: &quot;&pound;0.45 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 1401,
                &quot;title&quot;: &quot;123 Flowers&quot;,
                &quot;url_key&quot;: &quot;123-flowers&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/x8wSAtDUrW7QW17R_123-flow-160-logo.png&quot;,
                &quot;description&quot;: &quot;We are a leading online retailer that delivers flowers and gifts across the UK with an enviable reputation for excellent service and incredible conversion rate.&quot;,
                &quot;cashback&quot;: &quot;2.25% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 2860,
                &quot;title&quot;: &quot;123 Ink Cartridges&quot;,
                &quot;url_key&quot;: &quot;123-ink-cartridges&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/VHtP8GsZkGA5ylLT_160-logo.png&quot;,
                &quot;description&quot;: &quot;123 Ink Cartridges specialists in Epson, Canon and Brother printer inks with 5 star satisfaction, reliability speaking for itself. Our product range includes ink cartridges for every major brand of printer and an extensive range of laser toners, photo papers and original cartridges all 100% guaranteed.&quot;,
                &quot;cashback&quot;: &quot;5.40% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 3790,
                &quot;title&quot;: &quot;123Bookies&quot;,
                &quot;url_key&quot;: &quot;123bookies&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/lvaUVvvl2YyuZMcw_123-bookies160x140-logo-logo.png&quot;,
                &quot;description&quot;: &quot;To feature on 123Bookies, we review each online betting site as thoroughly as possible, making sure we only include the very best bookies. They need to demonstrate to us that they are one of the very best betting sites for UK players. In order to achieve a top rating.&quot;,
                &quot;cashback&quot;: &quot;&pound;0.20 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 1287,
                &quot;title&quot;: &quot;1CBD&quot;,
                &quot;url_key&quot;: &quot;1cbd&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/3dhIHmLm1kuU7rGp_cbd-160-logo.png&quot;,
                &quot;description&quot;: &quot;Our products provide many legitimate health benefits to people and are now one of the most exciting and high growth products in the UK health sector. We are the next big thing in Health.&quot;,
                &quot;cashback&quot;: &quot;6.75% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 4105,
                &quot;title&quot;: &quot;1ClickPrint&quot;,
                &quot;url_key&quot;: &quot;1clickprint&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/KJPCyeFOrHnFp3Bi_1ClickPrint-logo.png&quot;,
                &quot;description&quot;: &quot;Your memories deserve the very best. We believe that only by taking the time to create your finished print by hand can we make sure that your print arrives as you intended. No corner cutting, no excuses, just craftsmanship.&quot;,
                &quot;cashback&quot;: &quot;9.00% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 1685,
                &quot;title&quot;: &quot;1link Disposal Network&quot;,
                &quot;url_key&quot;: &quot;1link-disposal-network&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/kUHQWtgsVibVWQSs_1-logo.png&quot;,
                &quot;description&quot;: &quot;1link Disposal Network is the most comprehensive online car auction platform in the UK, giving you exclusive access to an extensive range of ex-fleet, leasing and manufacturer vehicles.&quot;,
                &quot;cashback&quot;: &quot;&pound;1.35 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 1090,
                &quot;title&quot;: &quot;1p Mobile&quot;,
                &quot;url_key&quot;: &quot;1p-mobile&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/H5l5cHcppQynicjS_1PMOBILE160-logo.png&quot;,
                &quot;description&quot;: &quot;We were formed with the purpose of providing a straightforward mobile tariff that offers great value for money to low and medium phone users. It's our mission to provide low cost calls, texts and data without asking people to commit to lengthy contracts or buy restrictive bundles which can lead to additional charges. With no contract to sign, no credit checks and the option of keeping your number, we are on a quest to save you money by offering the UK's cheapest PAYG mobile tariff.&quot;,
                &quot;cashback&quot;: &quot;31.50% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 3167,
                &quot;title&quot;: &quot;1pBroadband&quot;,
                &quot;url_key&quot;: &quot;1pbroadband&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/aplfxS6nim74gvZR_1pBroadband-logo.png&quot;,
                &quot;description&quot;: &quot;We are a UK company, based in Alton in Hampshire, with over 20 years of experience providing landline, broadband, and mobile services to thousands of satisfied customers. With highly competitive rates which include your landline rental charge and calls from as little as 1p per minute, we offer great value.&quot;,
                &quot;cashback&quot;: &quot;&pound;15.75 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 130,
                &quot;title&quot;: &quot;1st Birthday Gifts&quot;,
                &quot;url_key&quot;: &quot;1st-birthday-gifts&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/MlkMyOgiK7X9Zm3A_2nd-logo.png&quot;,
                &quot;description&quot;: &quot;1st Birthday Gifts is a Personalized Gift Online webstore with over 500 items for sale. We offer embroidered, printed and engraved items for any occasion, whether it's newborn baby or 1st Birthday, wedding or anniversary, baby Shower or Christening.&quot;,
                &quot;cashback&quot;: &quot;4.50% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 2098,
                &quot;title&quot;: &quot;20Cogs&quot;,
                &quot;url_key&quot;: &quot;20cogs&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/2evXbIeydlIhx7vl_Cog-logo.gif&quot;,
                &quot;description&quot;: &quot;Make money by completing online tasks &amp; offers. 20Cogs is a great way to earn a little extra cash from the comfort of your own home, on the train to work, in the park walking the dog... or wherever you like!&quot;,
                &quot;cashback&quot;: &quot;&pound;0.50 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 241,
                &quot;title&quot;: &quot;20i&quot;,
                &quot;url_key&quot;: &quot;20i&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/nFasNUA4VK0aEqRm_20ii-logo.png&quot;,
                &quot;description&quot;: &quot;We are 20i, the UK's top rated web hosting company. We deliver premium hosting experiences you can build your reputation on. We&rsquo;re a proudly-independent UK hosting company with a world-beating support team (number one on Trustpilot!).&quot;,
                &quot;cashback&quot;: &quot;&pound;4.50 Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 3694,
                &quot;title&quot;: &quot;247 Bargains &quot;,
                &quot;url_key&quot;: &quot;247-bargains&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/nQpB6U6OV0RdGzzk_Untitled-160-x-140-247-logo.gif&quot;,
                &quot;description&quot;: &quot;At 247 Bargains we utilize our bulk buying power across our group to allow us to offer unbeatable deals on awesome toys and collectables. 247 Bargains, the home of amazing savings and incredible bargains. Specializing in homeware, toys and games and collectables.&quot;,
                &quot;cashback&quot;: &quot;3.60% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 2626,
                &quot;title&quot;: &quot;247 Blinds&quot;,
                &quot;url_key&quot;: &quot;247-blinds&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/fhQIq3ppit1ZiGwk_247-Blinds-logo.png&quot;,
                &quot;description&quot;: &quot;Style, quality, choice and bargain prices are not often mentioned in the same sentence, but at 247 Blinds we have put together the largest range of top quality, made to measure blinds on the internet and still managed to keep our prices amongst the most competitive around.&quot;,
                &quot;cashback&quot;: &quot;2.25% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            },
            {
                &quot;id&quot;: 4490,
                &quot;title&quot;: &quot;280 Degrees African Restaurant&quot;,
                &quot;url_key&quot;: &quot;280-degrees-african-restaurant&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/upload/images/163651803413990050781664227744.jpeg&quot;,
                &quot;description&quot;: &quot;&lt;p&gt;Nigerian restaurant serving homestyle dishes.&lt;/p&gt;\r\n&quot;,
                &quot;cashback&quot;: &quot;0.25% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: &quot;51.54356700&quot;,
                &quot;long&quot;: &quot;-0.19958800&quot;
            },
            {
                &quot;id&quot;: 3122,
                &quot;title&quot;: &quot;304 Clothing&quot;,
                &quot;url_key&quot;: &quot;304-clothing&quot;,
                &quot;banner_image&quot;: null,
                &quot;big_icon&quot;: &quot;https://www.revglue.com/resources/common/store/7m4wLvHowwXHI8X7_304-clothing1630-logo.png&quot;,
                &quot;description&quot;: &quot;304 is bold, timeless streetwear for men, women, and juniors. What began as friends looking for adventure and freedom by printing t-shirts in apartment 304, has now become so much more reaching people all over the world with one-of-a-kind designs.&quot;,
                &quot;cashback&quot;: &quot;3.15% Cashback&quot;,
                &quot;is_fav&quot;: false,
                &quot;lat&quot;: null,
                &quot;long&quot;: null
            }
        ],
        &quot;meta_data&quot;: {
            &quot;next&quot;: &quot;http://127.0.0.1:8000/api/stores?page=2&quot;,
            &quot;previous&quot;: null,
            &quot;per_page&quot;: 20,
            &quot;total&quot;: 3609,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 181,
            &quot;first&quot;: 1,
            &quot;last&quot;: 20
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-stores" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-stores"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-stores"></code></pre>
</span>
<span id="execution-error-GETapi-stores" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-stores"></code></pre>
</span>
<form id="form-GETapi-stores" data-method="GET"
      data-path="api/stores"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-stores', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-stores"
                    onclick="tryItOut('GETapi-stores');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-stores"
                    onclick="cancelTryOut('GETapi-stores');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-stores" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/stores</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-stores--id-">GET api/stores/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-stores--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/stores/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/stores/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-stores--id-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Store not found&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-stores--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-stores--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-stores--id-"></code></pre>
</span>
<span id="execution-error-GETapi-stores--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-stores--id-"></code></pre>
</span>
<form id="form-GETapi-stores--id-" data-method="GET"
      data-path="api/stores/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-stores--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-stores--id-"
                    onclick="tryItOut('GETapi-stores--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-stores--id-"
                    onclick="cancelTryOut('GETapi-stores--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-stores--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/stores/{id}</code></b>
        </p>
                    <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <p>
                <b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
                <input type="number"
               name="id"
               data-endpoint="GETapi-stores--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the store.</p>
            </p>
                    </form>

            <h2 id="endpoints-GETapi-vouchers">GET api/vouchers</h2>

<p>
</p>



<span id="example-requests-GETapi-vouchers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/vouchers" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/vouchers"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-vouchers">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://127.0.0.1:8000/api/vouchers?per_page=10&amp;page=1&quot;,
        &quot;last&quot;: &quot;http://127.0.0.1:8000/api/vouchers?per_page=10&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;http://127.0.0.1:8000/api/vouchers?per_page=10&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;http://127.0.0.1:8000/api/vouchers&quot;,
        &quot;per_page&quot;: 10,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-vouchers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-vouchers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-vouchers"></code></pre>
</span>
<span id="execution-error-GETapi-vouchers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-vouchers"></code></pre>
</span>
<form id="form-GETapi-vouchers" data-method="GET"
      data-path="api/vouchers"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-vouchers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-vouchers"
                    onclick="tryItOut('GETapi-vouchers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-vouchers"
                    onclick="cancelTryOut('GETapi-vouchers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-vouchers" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/vouchers</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-verify_otp">POST api/verify_otp</h2>

<p>
</p>



<span id="example-requests-POSTapi-verify_otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/verify_otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"nolan.noemie@example.net\",
    \"otp\": \"njv\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/verify_otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "nolan.noemie@example.net",
    "otp": "njv"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-verify_otp">
</span>
<span id="execution-results-POSTapi-verify_otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-verify_otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-verify_otp"></code></pre>
</span>
<span id="execution-error-POSTapi-verify_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-verify_otp"></code></pre>
</span>
<form id="form-POSTapi-verify_otp" data-method="POST"
      data-path="api/verify_otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-verify_otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-verify_otp"
                    onclick="tryItOut('POSTapi-verify_otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-verify_otp"
                    onclick="cancelTryOut('POSTapi-verify_otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-verify_otp" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/verify_otp</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="email"
               data-endpoint="POSTapi-verify_otp"
               value="nolan.noemie@example.net"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address.</p>
        </p>
                <p>
            <b><code>otp</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="otp"
               data-endpoint="POSTapi-verify_otp"
               value="njv"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 6 characters.</p>
        </p>
        </form>

            <h2 id="endpoints-POSTapi-resend_otp">POST api/resend_otp</h2>

<p>
</p>



<span id="example-requests-POSTapi-resend_otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/resend_otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"isaac70@example.net\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/resend_otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "isaac70@example.net"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-resend_otp">
</span>
<span id="execution-results-POSTapi-resend_otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-resend_otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-resend_otp"></code></pre>
</span>
<span id="execution-error-POSTapi-resend_otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-resend_otp"></code></pre>
</span>
<form id="form-POSTapi-resend_otp" data-method="POST"
      data-path="api/resend_otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-resend_otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-resend_otp"
                    onclick="tryItOut('POSTapi-resend_otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-resend_otp"
                    onclick="cancelTryOut('POSTapi-resend_otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-resend_otp" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/resend_otp</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="email"
               data-endpoint="POSTapi-resend_otp"
               value="isaac70@example.net"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address.</p>
        </p>
        </form>

            <h2 id="endpoints-POSTapi-main_search">POST api/main_search</h2>

<p>
</p>



<span id="example-requests-POSTapi-main_search">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/main_search" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/main_search"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-main_search">
</span>
<span id="execution-results-POSTapi-main_search" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-main_search"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-main_search"></code></pre>
</span>
<span id="execution-error-POSTapi-main_search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-main_search"></code></pre>
</span>
<form id="form-POSTapi-main_search" data-method="POST"
      data-path="api/main_search"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-main_search', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-main_search"
                    onclick="tryItOut('POSTapi-main_search');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-main_search"
                    onclick="cancelTryOut('POSTapi-main_search');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-main_search" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/main_search</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-auth-logout">GET api/auth/logout</h2>

<p>
</p>



<span id="example-requests-GETapi-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/auth/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/auth/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-auth-logout">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-logout"></code></pre>
</span>
<span id="execution-error-GETapi-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-logout"></code></pre>
</span>
<form id="form-GETapi-auth-logout" data-method="GET"
      data-path="api/auth/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-auth-logout"
                    onclick="tryItOut('GETapi-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-auth-logout"
                    onclick="cancelTryOut('GETapi-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-auth-logout" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/auth/logout</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-update_profile">POST api/update_profile</h2>

<p>
</p>



<span id="example-requests-POSTapi-update_profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/update_profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"firstname\": \"qjymxpdxcmyljmanzgsspjkgspqhmrhcosrxhclkmgfpkqkofyfgwvmmpzmepgkypglaoplosbjuckbvnvhbpzjqygbhozl\",
    \"lastname\": \"ykpcbkmspnanesynugakoikduxnophzergrwnydsmyrremfvgofpamuaidfzhvtnbkyapntiauoalgxlsmlhzvdogcrmcd\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/update_profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "firstname": "qjymxpdxcmyljmanzgsspjkgspqhmrhcosrxhclkmgfpkqkofyfgwvmmpzmepgkypglaoplosbjuckbvnvhbpzjqygbhozl",
    "lastname": "ykpcbkmspnanesynugakoikduxnophzergrwnydsmyrremfvgofpamuaidfzhvtnbkyapntiauoalgxlsmlhzvdogcrmcd"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-update_profile">
</span>
<span id="execution-results-POSTapi-update_profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-update_profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-update_profile"></code></pre>
</span>
<span id="execution-error-POSTapi-update_profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-update_profile"></code></pre>
</span>
<form id="form-POSTapi-update_profile" data-method="POST"
      data-path="api/update_profile"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-update_profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-update_profile"
                    onclick="tryItOut('POSTapi-update_profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-update_profile"
                    onclick="cancelTryOut('POSTapi-update_profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-update_profile" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/update_profile</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>firstname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="firstname"
               data-endpoint="POSTapi-update_profile"
               value="qjymxpdxcmyljmanzgsspjkgspqhmrhcosrxhclkmgfpkqkofyfgwvmmpzmepgkypglaoplosbjuckbvnvhbpzjqygbhozl"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>lastname</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="lastname"
               data-endpoint="POSTapi-update_profile"
               value="ykpcbkmspnanesynugakoikduxnophzergrwnydsmyrremfvgofpamuaidfzhvtnbkyapntiauoalgxlsmlhzvdogcrmcd"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
        </form>

            <h2 id="endpoints-POSTapi-update_avatar">POST api/update_avatar</h2>

<p>
</p>



<span id="example-requests-POSTapi-update_avatar">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/update_avatar" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/update_avatar"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-update_avatar">
</span>
<span id="execution-results-POSTapi-update_avatar" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-update_avatar"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-update_avatar"></code></pre>
</span>
<span id="execution-error-POSTapi-update_avatar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-update_avatar"></code></pre>
</span>
<form id="form-POSTapi-update_avatar" data-method="POST"
      data-path="api/update_avatar"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-update_avatar', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-update_avatar"
                    onclick="tryItOut('POSTapi-update_avatar');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-update_avatar"
                    onclick="cancelTryOut('POSTapi-update_avatar');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-update_avatar" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/update_avatar</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-user_data">GET api/user_data</h2>

<p>
</p>



<span id="example-requests-GETapi-user_data">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/user_data" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user_data"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user_data">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user_data" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user_data"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user_data"></code></pre>
</span>
<span id="execution-error-GETapi-user_data" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user_data"></code></pre>
</span>
<form id="form-GETapi-user_data" data-method="GET"
      data-path="api/user_data"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user_data', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user_data"
                    onclick="tryItOut('GETapi-user_data');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user_data"
                    onclick="cancelTryOut('GETapi-user_data');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user_data" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user_data</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-user_bank_details">POST api/user_bank_details</h2>

<p>
</p>



<span id="example-requests-POSTapi-user_bank_details">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user_bank_details" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user_bank_details"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user_bank_details">
</span>
<span id="execution-results-POSTapi-user_bank_details" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user_bank_details"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user_bank_details"></code></pre>
</span>
<span id="execution-error-POSTapi-user_bank_details" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user_bank_details"></code></pre>
</span>
<form id="form-POSTapi-user_bank_details" data-method="POST"
      data-path="api/user_bank_details"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user_bank_details', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user_bank_details"
                    onclick="tryItOut('POSTapi-user_bank_details');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user_bank_details"
                    onclick="cancelTryOut('POSTapi-user_bank_details');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user_bank_details" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user_bank_details</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-user-cashback">POST api/user/cashback</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-cashback">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user/cashback" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/cashback"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-cashback">
</span>
<span id="execution-results-POSTapi-user-cashback" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-cashback"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-cashback"></code></pre>
</span>
<span id="execution-error-POSTapi-user-cashback" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-cashback"></code></pre>
</span>
<form id="form-POSTapi-user-cashback" data-method="POST"
      data-path="api/user/cashback"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-cashback', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-cashback"
                    onclick="tryItOut('POSTapi-user-cashback');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-cashback"
                    onclick="cancelTryOut('POSTapi-user-cashback');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-cashback" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/cashback</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-user-clicks">POST api/user/clicks</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-clicks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user/clicks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/clicks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-clicks">
</span>
<span id="execution-results-POSTapi-user-clicks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-clicks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-clicks"></code></pre>
</span>
<span id="execution-error-POSTapi-user-clicks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-clicks"></code></pre>
</span>
<form id="form-POSTapi-user-clicks" data-method="POST"
      data-path="api/user/clicks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-clicks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-clicks"
                    onclick="tryItOut('POSTapi-user-clicks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-clicks"
                    onclick="cancelTryOut('POSTapi-user-clicks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-clicks" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/clicks</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-user-tickets">POST api/user/tickets</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-tickets">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user/tickets" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/tickets"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-tickets">
</span>
<span id="execution-results-POSTapi-user-tickets" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-tickets"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-tickets"></code></pre>
</span>
<span id="execution-error-POSTapi-user-tickets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-tickets"></code></pre>
</span>
<form id="form-POSTapi-user-tickets" data-method="POST"
      data-path="api/user/tickets"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-tickets', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-tickets"
                    onclick="tryItOut('POSTapi-user-tickets');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-tickets"
                    onclick="cancelTryOut('POSTapi-user-tickets');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-tickets" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/tickets</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-user-referral-data">POST api/user/referral-data</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-referral-data">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user/referral-data" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/referral-data"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-referral-data">
</span>
<span id="execution-results-POSTapi-user-referral-data" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-referral-data"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-referral-data"></code></pre>
</span>
<span id="execution-error-POSTapi-user-referral-data" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-referral-data"></code></pre>
</span>
<form id="form-POSTapi-user-referral-data" data-method="POST"
      data-path="api/user/referral-data"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-referral-data', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-referral-data"
                    onclick="tryItOut('POSTapi-user-referral-data');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-referral-data"
                    onclick="cancelTryOut('POSTapi-user-referral-data');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-referral-data" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/referral-data</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-refer_and_earn">GET api/refer_and_earn</h2>

<p>
</p>



<span id="example-requests-GETapi-refer_and_earn">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/refer_and_earn" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/refer_and_earn"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-refer_and_earn">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-refer_and_earn" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-refer_and_earn"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-refer_and_earn"></code></pre>
</span>
<span id="execution-error-GETapi-refer_and_earn" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-refer_and_earn"></code></pre>
</span>
<form id="form-GETapi-refer_and_earn" data-method="GET"
      data-path="api/refer_and_earn"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-refer_and_earn', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-refer_and_earn"
                    onclick="tryItOut('GETapi-refer_and_earn');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-refer_and_earn"
                    onclick="cancelTryOut('GETapi-refer_and_earn');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-refer_and_earn" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/refer_and_earn</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-send_refer_email">POST api/send_refer_email</h2>

<p>
</p>



<span id="example-requests-POSTapi-send_refer_email">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/send_refer_email" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"referral_email\": \"olesch@example.net\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/send_refer_email"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "referral_email": "olesch@example.net"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-send_refer_email">
</span>
<span id="execution-results-POSTapi-send_refer_email" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-send_refer_email"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-send_refer_email"></code></pre>
</span>
<span id="execution-error-POSTapi-send_refer_email" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-send_refer_email"></code></pre>
</span>
<form id="form-POSTapi-send_refer_email" data-method="POST"
      data-path="api/send_refer_email"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-send_refer_email', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-send_refer_email"
                    onclick="tryItOut('POSTapi-send_refer_email');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-send_refer_email"
                    onclick="cancelTryOut('POSTapi-send_refer_email');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-send_refer_email" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/send_refer_email</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>referral_email</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="referral_email"
               data-endpoint="POSTapi-send_refer_email"
               value="olesch@example.net"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address.</p>
        </p>
        </form>

            <h2 id="endpoints-POSTapi-user-cashouts">POST api/user/cashouts</h2>

<p>
</p>



<span id="example-requests-POSTapi-user-cashouts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/user/cashouts" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/user/cashouts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-user-cashouts">
</span>
<span id="execution-results-POSTapi-user-cashouts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-user-cashouts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-user-cashouts"></code></pre>
</span>
<span id="execution-error-POSTapi-user-cashouts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-user-cashouts"></code></pre>
</span>
<form id="form-POSTapi-user-cashouts" data-method="POST"
      data-path="api/user/cashouts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-user-cashouts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-user-cashouts"
                    onclick="tryItOut('POSTapi-user-cashouts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-user-cashouts"
                    onclick="cancelTryOut('POSTapi-user-cashouts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-user-cashouts" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/user/cashouts</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-create_ticket">POST api/create_ticket</h2>

<p>
</p>



<span id="example-requests-POSTapi-create_ticket">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/create_ticket" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/create_ticket"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-create_ticket">
</span>
<span id="execution-results-POSTapi-create_ticket" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-create_ticket"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-create_ticket"></code></pre>
</span>
<span id="execution-error-POSTapi-create_ticket" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-create_ticket"></code></pre>
</span>
<form id="form-POSTapi-create_ticket" data-method="POST"
      data-path="api/create_ticket"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-create_ticket', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-create_ticket"
                    onclick="tryItOut('POSTapi-create_ticket');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-create_ticket"
                    onclick="cancelTryOut('POSTapi-create_ticket');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-create_ticket" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/create_ticket</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-ticket_stores">GET api/ticket_stores</h2>

<p>
</p>



<span id="example-requests-GETapi-ticket_stores">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/ticket_stores" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/ticket_stores"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-ticket_stores">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-ticket_stores" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-ticket_stores"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-ticket_stores"></code></pre>
</span>
<span id="execution-error-GETapi-ticket_stores" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-ticket_stores"></code></pre>
</span>
<form id="form-GETapi-ticket_stores" data-method="GET"
      data-path="api/ticket_stores"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-ticket_stores', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-ticket_stores"
                    onclick="tryItOut('GETapi-ticket_stores');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-ticket_stores"
                    onclick="cancelTryOut('GETapi-ticket_stores');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-ticket_stores" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/ticket_stores</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-ticket_clicks">POST api/ticket_clicks</h2>

<p>
</p>



<span id="example-requests-POSTapi-ticket_clicks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/ticket_clicks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/ticket_clicks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-ticket_clicks">
</span>
<span id="execution-results-POSTapi-ticket_clicks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-ticket_clicks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-ticket_clicks"></code></pre>
</span>
<span id="execution-error-POSTapi-ticket_clicks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-ticket_clicks"></code></pre>
</span>
<form id="form-POSTapi-ticket_clicks" data-method="POST"
      data-path="api/ticket_clicks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-ticket_clicks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-ticket_clicks"
                    onclick="tryItOut('POSTapi-ticket_clicks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-ticket_clicks"
                    onclick="cancelTryOut('POSTapi-ticket_clicks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-ticket_clicks" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/ticket_clicks</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-get-fav-stores">POST api/get-fav-stores</h2>

<p>
</p>



<span id="example-requests-POSTapi-get-fav-stores">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/get-fav-stores" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/get-fav-stores"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-get-fav-stores">
</span>
<span id="execution-results-POSTapi-get-fav-stores" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-get-fav-stores"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-get-fav-stores"></code></pre>
</span>
<span id="execution-error-POSTapi-get-fav-stores" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-get-fav-stores"></code></pre>
</span>
<form id="form-POSTapi-get-fav-stores" data-method="POST"
      data-path="api/get-fav-stores"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-get-fav-stores', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-get-fav-stores"
                    onclick="tryItOut('POSTapi-get-fav-stores');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-get-fav-stores"
                    onclick="cancelTryOut('POSTapi-get-fav-stores');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-get-fav-stores" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/get-fav-stores</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-get-fav-cbdoor">POST api/get-fav-cbdoor</h2>

<p>
</p>



<span id="example-requests-POSTapi-get-fav-cbdoor">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/get-fav-cbdoor" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/get-fav-cbdoor"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-get-fav-cbdoor">
</span>
<span id="execution-results-POSTapi-get-fav-cbdoor" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-get-fav-cbdoor"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-get-fav-cbdoor"></code></pre>
</span>
<span id="execution-error-POSTapi-get-fav-cbdoor" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-get-fav-cbdoor"></code></pre>
</span>
<form id="form-POSTapi-get-fav-cbdoor" data-method="POST"
      data-path="api/get-fav-cbdoor"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-get-fav-cbdoor', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-get-fav-cbdoor"
                    onclick="tryItOut('POSTapi-get-fav-cbdoor');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-get-fav-cbdoor"
                    onclick="cancelTryOut('POSTapi-get-fav-cbdoor');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-get-fav-cbdoor" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/get-fav-cbdoor</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-add-fav-store">Display the specified resource.</h2>

<p>
</p>



<span id="example-requests-POSTapi-add-fav-store">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/add-fav-store" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/add-fav-store"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-add-fav-store">
</span>
<span id="execution-results-POSTapi-add-fav-store" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-add-fav-store"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-add-fav-store"></code></pre>
</span>
<span id="execution-error-POSTapi-add-fav-store" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-add-fav-store"></code></pre>
</span>
<form id="form-POSTapi-add-fav-store" data-method="POST"
      data-path="api/add-fav-store"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-add-fav-store', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-add-fav-store"
                    onclick="tryItOut('POSTapi-add-fav-store');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-add-fav-store"
                    onclick="cancelTryOut('POSTapi-add-fav-store');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-add-fav-store" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/add-fav-store</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-remove-fav-store">POST api/remove-fav-store</h2>

<p>
</p>



<span id="example-requests-POSTapi-remove-fav-store">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/remove-fav-store" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/remove-fav-store"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-remove-fav-store">
</span>
<span id="execution-results-POSTapi-remove-fav-store" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-remove-fav-store"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-remove-fav-store"></code></pre>
</span>
<span id="execution-error-POSTapi-remove-fav-store" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-remove-fav-store"></code></pre>
</span>
<form id="form-POSTapi-remove-fav-store" data-method="POST"
      data-path="api/remove-fav-store"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-remove-fav-store', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-remove-fav-store"
                    onclick="tryItOut('POSTapi-remove-fav-store');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-remove-fav-store"
                    onclick="cancelTryOut('POSTapi-remove-fav-store');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-remove-fav-store" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/remove-fav-store</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-dashboard_data">Display a listing of the resource.</h2>

<p>
</p>



<span id="example-requests-POSTapi-dashboard_data">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/dashboard_data" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/dashboard_data"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-dashboard_data">
</span>
<span id="execution-results-POSTapi-dashboard_data" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-dashboard_data"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-dashboard_data"></code></pre>
</span>
<span id="execution-error-POSTapi-dashboard_data" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-dashboard_data"></code></pre>
</span>
<form id="form-POSTapi-dashboard_data" data-method="POST"
      data-path="api/dashboard_data"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-dashboard_data', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-dashboard_data"
                    onclick="tryItOut('POSTapi-dashboard_data');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-dashboard_data"
                    onclick="cancelTryOut('POSTapi-dashboard_data');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-dashboard_data" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/dashboard_data</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-charities">POST api/charities</h2>

<p>
</p>



<span id="example-requests-POSTapi-charities">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/charities" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/charities"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-charities">
</span>
<span id="execution-results-POSTapi-charities" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-charities"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-charities"></code></pre>
</span>
<span id="execution-error-POSTapi-charities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-charities"></code></pre>
</span>
<form id="form-POSTapi-charities" data-method="POST"
      data-path="api/charities"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-charities', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-charities"
                    onclick="tryItOut('POSTapi-charities');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-charities"
                    onclick="cancelTryOut('POSTapi-charities');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-charities" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/charities</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-withdraw">POST api/withdraw</h2>

<p>
</p>



<span id="example-requests-POSTapi-withdraw">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/withdraw" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/withdraw"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-withdraw">
</span>
<span id="execution-results-POSTapi-withdraw" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-withdraw"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-withdraw"></code></pre>
</span>
<span id="execution-error-POSTapi-withdraw" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-withdraw"></code></pre>
</span>
<form id="form-POSTapi-withdraw" data-method="POST"
      data-path="api/withdraw"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-withdraw', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-withdraw"
                    onclick="tryItOut('POSTapi-withdraw');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-withdraw"
                    onclick="cancelTryOut('POSTapi-withdraw');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-withdraw" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/withdraw</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-charity_withdraw">POST api/charity_withdraw</h2>

<p>
</p>



<span id="example-requests-POSTapi-charity_withdraw">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/charity_withdraw" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/charity_withdraw"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-charity_withdraw">
</span>
<span id="execution-results-POSTapi-charity_withdraw" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-charity_withdraw"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-charity_withdraw"></code></pre>
</span>
<span id="execution-error-POSTapi-charity_withdraw" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-charity_withdraw"></code></pre>
</span>
<form id="form-POSTapi-charity_withdraw" data-method="POST"
      data-path="api/charity_withdraw"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-charity_withdraw', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-charity_withdraw"
                    onclick="tryItOut('POSTapi-charity_withdraw');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-charity_withdraw"
                    onclick="cancelTryOut('POSTapi-charity_withdraw');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-charity_withdraw" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/charity_withdraw</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-charity_user_cashbacks_and_types">GET api/charity_user_cashbacks_and_types</h2>

<p>
</p>



<span id="example-requests-GETapi-charity_user_cashbacks_and_types">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/charity_user_cashbacks_and_types" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/charity_user_cashbacks_and_types"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-charity_user_cashbacks_and_types">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-charity_user_cashbacks_and_types" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-charity_user_cashbacks_and_types"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-charity_user_cashbacks_and_types"></code></pre>
</span>
<span id="execution-error-GETapi-charity_user_cashbacks_and_types" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-charity_user_cashbacks_and_types"></code></pre>
</span>
<form id="form-GETapi-charity_user_cashbacks_and_types" data-method="GET"
      data-path="api/charity_user_cashbacks_and_types"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-charity_user_cashbacks_and_types', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-charity_user_cashbacks_and_types"
                    onclick="tryItOut('GETapi-charity_user_cashbacks_and_types');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-charity_user_cashbacks_and_types"
                    onclick="cancelTryOut('GETapi-charity_user_cashbacks_and_types');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-charity_user_cashbacks_and_types" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/charity_user_cashbacks_and_types</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-enable_notifications">POST api/enable_notifications</h2>

<p>
</p>



<span id="example-requests-POSTapi-enable_notifications">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/enable_notifications" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"fcmtoken\": \"zfxvwapvlhnhgugdosxmsgtzlqrfqfjhpdhhqqskwjqpgnbkbgrkhiop\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/enable_notifications"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "fcmtoken": "zfxvwapvlhnhgugdosxmsgtzlqrfqfjhpdhhqqskwjqpgnbkbgrkhiop"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-enable_notifications">
</span>
<span id="execution-results-POSTapi-enable_notifications" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-enable_notifications"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-enable_notifications"></code></pre>
</span>
<span id="execution-error-POSTapi-enable_notifications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-enable_notifications"></code></pre>
</span>
<form id="form-POSTapi-enable_notifications" data-method="POST"
      data-path="api/enable_notifications"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-enable_notifications', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-enable_notifications"
                    onclick="tryItOut('POSTapi-enable_notifications');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-enable_notifications"
                    onclick="cancelTryOut('POSTapi-enable_notifications');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-enable_notifications" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/enable_notifications</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>fcmtoken</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="fcmtoken"
               data-endpoint="POSTapi-enable_notifications"
               value="zfxvwapvlhnhgugdosxmsgtzlqrfqfjhpdhhqqskwjqpgnbkbgrkhiop"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
        </form>

            <h2 id="endpoints-GETapi-disable_notifications">GET api/disable_notifications</h2>

<p>
</p>



<span id="example-requests-GETapi-disable_notifications">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/disable_notifications" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/disable_notifications"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-disable_notifications">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Not authenticated User&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-disable_notifications" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-disable_notifications"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-disable_notifications"></code></pre>
</span>
<span id="execution-error-GETapi-disable_notifications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-disable_notifications"></code></pre>
</span>
<form id="form-GETapi-disable_notifications" data-method="GET"
      data-path="api/disable_notifications"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-disable_notifications', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-disable_notifications"
                    onclick="tryItOut('GETapi-disable_notifications');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-disable_notifications"
                    onclick="cancelTryOut('GETapi-disable_notifications');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-disable_notifications" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/disable_notifications</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-afrobot">POST api/afrobot</h2>

<p>
</p>



<span id="example-requests-POSTapi-afrobot">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/afrobot" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/afrobot"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-afrobot">
</span>
<span id="execution-results-POSTapi-afrobot" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-afrobot"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-afrobot"></code></pre>
</span>
<span id="execution-error-POSTapi-afrobot" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-afrobot"></code></pre>
</span>
<form id="form-POSTapi-afrobot" data-method="POST"
      data-path="api/afrobot"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-afrobot', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-afrobot"
                    onclick="tryItOut('POSTapi-afrobot');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-afrobot"
                    onclick="cancelTryOut('POSTapi-afrobot');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-afrobot" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/afrobot</code></b>
        </p>
                    </form>

            <h2 id="endpoints-POSTapi-competitor-stores">POST api/competitor-stores</h2>

<p>
</p>



<span id="example-requests-POSTapi-competitor-stores">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/competitor-stores" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/competitor-stores"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-competitor-stores">
</span>
<span id="execution-results-POSTapi-competitor-stores" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-competitor-stores"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-competitor-stores"></code></pre>
</span>
<span id="execution-error-POSTapi-competitor-stores" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-competitor-stores"></code></pre>
</span>
<form id="form-POSTapi-competitor-stores" data-method="POST"
      data-path="api/competitor-stores"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-competitor-stores', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-competitor-stores"
                    onclick="tryItOut('POSTapi-competitor-stores');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-competitor-stores"
                    onclick="cancelTryOut('POSTapi-competitor-stores');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-competitor-stores" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/competitor-stores</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-store-detail--id-">GET api/store-detail/{id}</h2>

<p>
</p>



<span id="example-requests-GETapi-store-detail--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/store-detail/ea" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/store-detail/ea"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-store-detail--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 56
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;data&quot;: [],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://127.0.0.1:8000/api/store-detail/ea?per_page=10&amp;page=1&quot;,
        &quot;last&quot;: &quot;http://127.0.0.1:8000/api/store-detail/ea?per_page=10&amp;page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: null,
        &quot;last_page&quot;: 1,
        &quot;links&quot;: [
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;&amp;laquo; Previous&quot;,
                &quot;active&quot;: false
            },
            {
                &quot;url&quot;: &quot;http://127.0.0.1:8000/api/store-detail/ea?per_page=10&amp;page=1&quot;,
                &quot;label&quot;: &quot;1&quot;,
                &quot;active&quot;: true
            },
            {
                &quot;url&quot;: null,
                &quot;label&quot;: &quot;Next &amp;raquo;&quot;,
                &quot;active&quot;: false
            }
        ],
        &quot;path&quot;: &quot;http://127.0.0.1:8000/api/store-detail/ea&quot;,
        &quot;per_page&quot;: 10,
        &quot;to&quot;: null,
        &quot;total&quot;: 0
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-store-detail--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-store-detail--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-store-detail--id-"></code></pre>
</span>
<span id="execution-error-GETapi-store-detail--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-store-detail--id-"></code></pre>
</span>
<form id="form-GETapi-store-detail--id-" data-method="GET"
      data-path="api/store-detail/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-store-detail--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-store-detail--id-"
                    onclick="tryItOut('GETapi-store-detail--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-store-detail--id-"
                    onclick="cancelTryOut('GETapi-store-detail--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-store-detail--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/store-detail/{id}</code></b>
        </p>
                    <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <p>
                <b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="id"
               data-endpoint="GETapi-store-detail--id-"
               value="ea"
               data-component="url" hidden>
    <br>
<p>The ID of the store detail.</p>
            </p>
                    </form>

            <h2 id="endpoints-GETapi-home">GET api/home</h2>

<p>
</p>



<span id="example-requests-GETapi-home">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/home" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/home"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-home">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 55
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;base_url&quot;: &quot;http://127.0.0.1:8000&quot;,
        &quot;main_banner_images&quot;: [
            {
                &quot;order&quot;: &quot;1&quot;,
                &quot;banner_type&quot;: &quot;store&quot;,
                &quot;banner_logo&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/logo/default1.png&quot;,
                &quot;banner_image&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/default1.png&quot;,
                &quot;description&quot;: &quot;Get everything at a huge discount&quot;,
                &quot;link&quot;: null,
                &quot;url_key&quot;: &quot;sleep-and-snooze&quot;,
                &quot;title&quot;: &quot;Sleep and Snooze&quot;
            },
            {
                &quot;order&quot;: &quot;2&quot;,
                &quot;banner_type&quot;: &quot;store&quot;,
                &quot;banner_logo&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/logo/default2.png&quot;,
                &quot;banner_image&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/default2.png&quot;,
                &quot;description&quot;: &quot;Get everything at a huge discount&quot;,
                &quot;link&quot;: null,
                &quot;url_key&quot;: &quot;silver-sovereign&quot;,
                &quot;title&quot;: &quot;Silver Sovereign&quot;
            },
            {
                &quot;order&quot;: &quot;3&quot;,
                &quot;banner_type&quot;: &quot;store&quot;,
                &quot;banner_logo&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/logo/default3.png&quot;,
                &quot;banner_image&quot;: &quot;http://127.0.0.1:8000/frontend/images/slides/default3.png&quot;,
                &quot;description&quot;: &quot;Get everything at a huge discount&quot;,
                &quot;link&quot;: null,
                &quot;url_key&quot;: &quot;al-fresco-holidays&quot;,
                &quot;title&quot;: &quot;Al Fresco Holidays&quot;
            }
        ],
        &quot;featured_stores&quot;: [],
        &quot;featured_categories&quot;: [],
        &quot;top_offers&quot;: []
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-home" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-home"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-home"></code></pre>
</span>
<span id="execution-error-GETapi-home" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-home"></code></pre>
</span>
<form id="form-GETapi-home" data-method="GET"
      data-path="api/home"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-home', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-home"
                    onclick="tryItOut('GETapi-home');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-home"
                    onclick="cancelTryOut('GETapi-home');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-home" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/home</code></b>
        </p>
                    </form>

            <h2 id="endpoints-GETapi-categories--letter--">GET api/categories/{letter?}</h2>

<p>
</p>



<span id="example-requests-GETapi-categories--letter--">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/categories/quos" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/categories/quos"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-categories--letter--">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 54
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;main_banner&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
        &quot;categories&quot;: [
            {
                &quot;title&quot;: &quot;More&quot;,
                &quot;url_key&quot;: &quot;more&quot;,
                &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/zDNgrbh8ryYr9Lt4_more-banner.png&quot;,
                &quot;description&quot;: &quot;More&quot;,
                &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/JcfTa3Il1cHYROPH_more-banner.jpg&quot;,
                &quot;cat_stores_count&quot;: 694,
                &quot;subcats&quot;: [
                    {
                        &quot;id&quot;: 98,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Adult&quot;,
                        &quot;url_key&quot;: &quot;adult&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/3NjJF7hHgm231B0r_adult-banner.png&quot;,
                        &quot;description&quot;: &quot;Adult&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/f8KTiIC4sC8Sv3Cx_adult-dating-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 36
                    },
                    {
                        &quot;id&quot;: 100,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Automotive&quot;,
                        &quot;url_key&quot;: &quot;automotive&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/J7VxZWVcrb5enPQt_automotive-banner.png&quot;,
                        &quot;description&quot;: &quot;Automotive&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/ODPp9sW7zn0lEUgK_automotive-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 105
                    },
                    {
                        &quot;id&quot;: 138,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;CBD &amp; E-juice&quot;,
                        &quot;url_key&quot;: &quot;cbd-e-juice&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/ola83Sfn0QWBpD94_cbd-ejuice-banner.png&quot;,
                        &quot;description&quot;: &quot;CBD &amp; E-juice&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/CiEVHjS463b1DtAs_cbd-ejuice-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 72
                    },
                    {
                        &quot;id&quot;: 99,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Education &amp; Learning&quot;,
                        &quot;url_key&quot;: &quot;education-learning&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/A1xKz6PjGyouhGDf_education-banner.png&quot;,
                        &quot;description&quot;: &quot;Education &amp; Learning&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/u9hi0Oq6Xev9jnro_education-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 65
                    },
                    {
                        &quot;id&quot;: 151,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Law&quot;,
                        &quot;url_key&quot;: &quot;law&quot;,
                        &quot;icon&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
                        &quot;description&quot;: null,
                        &quot;main_banner&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
                        &quot;subcat_stores_count&quot;: 2
                    },
                    {
                        &quot;id&quot;: 97,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Luxury&quot;,
                        &quot;url_key&quot;: &quot;luxury&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/OZuuCqVv65EWazDf_luxury-banner.png&quot;,
                        &quot;description&quot;: &quot;Luxury&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/XctsbYGLU4DvRWza_luxury-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 81
                    },
                    {
                        &quot;id&quot;: 95,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Parents &amp; Kids&quot;,
                        &quot;url_key&quot;: &quot;parents-kids&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/kacKhhmRDnHkEiFQ_parents-kids-banner.png&quot;,
                        &quot;description&quot;: &quot;Parents &amp; Kids&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/GM3PlvZPMyzDrbCl_parents-kids-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 100
                    },
                    {
                        &quot;id&quot;: 94,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Pets&quot;,
                        &quot;url_key&quot;: &quot;pets&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/6GGZbk2uQJdmqcQZ_pets-banner.png&quot;,
                        &quot;description&quot;: &quot;Pets&quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/hS0NdOGx2eOF1mVJ_pets-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 118
                    },
                    {
                        &quot;id&quot;: 152,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Professional Services&quot;,
                        &quot;url_key&quot;: &quot;professional-services&quot;,
                        &quot;icon&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
                        &quot;description&quot;: null,
                        &quot;main_banner&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
                        &quot;subcat_stores_count&quot;: 6
                    },
                    {
                        &quot;id&quot;: 101,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Subscription Boxes &quot;,
                        &quot;url_key&quot;: &quot;subscription-boxes&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/ye8e6hp0dnN6YS1y_subscription-box-banner.png&quot;,
                        &quot;description&quot;: &quot;Subscription Boxes &quot;,
                        &quot;main_banner&quot;: &quot;https://www.revglue.com/resources/common/banner/sYqCFMN3ZTaAupHy_subscription-boxes-banner.jpg&quot;,
                        &quot;subcat_stores_count&quot;: 106
                    },
                    {
                        &quot;id&quot;: 102,
                        &quot;parent_id&quot;: 93,
                        &quot;title&quot;: &quot;Wedding&quot;,
                        &quot;url_key&quot;: &quot;wedding&quot;,
                        &quot;icon&quot;: &quot;https://www.revglue.com/resources/common/banner/EBjNwJ1Vz0K97qL0_wedding-banner.png&quot;,
                        &quot;description&quot;: &quot;Shop and earn cashback from Black-owned retailers for clothing, gifts or anything else you might need whether it's yours or a loved ones special day.&quot;,
                        &quot;main_banner&quot;: &quot;http://127.0.0.1:8000/frontend/images/banners/categories/cashback.png&quot;,
                        &quot;subcat_stores_count&quot;: 27
                    }
                ]
            }
        ],
        &quot;meta_data&quot;: {
            &quot;next&quot;: null,
            &quot;previous&quot;: null,
            &quot;per_page&quot;: 20,
            &quot;total&quot;: 0,
            &quot;current_page&quot;: 1,
            &quot;total_pages&quot;: 1,
            &quot;first&quot;: 1,
            &quot;last&quot;: 1
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-categories--letter--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-categories--letter--"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-categories--letter--"></code></pre>
</span>
<span id="execution-error-GETapi-categories--letter--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-categories--letter--"></code></pre>
</span>
<form id="form-GETapi-categories--letter--" data-method="GET"
      data-path="api/categories/{letter?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-categories--letter--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-categories--letter--"
                    onclick="tryItOut('GETapi-categories--letter--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-categories--letter--"
                    onclick="cancelTryOut('GETapi-categories--letter--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-categories--letter--" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/categories/{letter?}</code></b>
        </p>
                    <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <p>
                <b><code>letter</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="letter"
               data-endpoint="GETapi-categories--letter--"
               value="quos"
               data-component="url" hidden>
    <br>

            </p>
                    </form>

            <h2 id="endpoints-GETapi-child-categories--slug---letter--">GET api/child-categories/{slug}/{letter?}</h2>

<p>
</p>



<span id="example-requests-GETapi-child-categories--slug---letter--">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/child-categories/repellat/voluptatem" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/child-categories/repellat/voluptatem"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-child-categories--slug---letter--">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 53
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;No store found&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-child-categories--slug---letter--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-child-categories--slug---letter--"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-child-categories--slug---letter--"></code></pre>
</span>
<span id="execution-error-GETapi-child-categories--slug---letter--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-child-categories--slug---letter--"></code></pre>
</span>
<form id="form-GETapi-child-categories--slug---letter--" data-method="GET"
      data-path="api/child-categories/{slug}/{letter?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-child-categories--slug---letter--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-child-categories--slug---letter--"
                    onclick="tryItOut('GETapi-child-categories--slug---letter--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-child-categories--slug---letter--"
                    onclick="cancelTryOut('GETapi-child-categories--slug---letter--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-child-categories--slug---letter--" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/child-categories/{slug}/{letter?}</code></b>
        </p>
                    <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <p>
                <b><code>slug</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="slug"
               data-endpoint="GETapi-child-categories--slug---letter--"
               value="repellat"
               data-component="url" hidden>
    <br>
<p>The slug of the child category.</p>
            </p>
                    <p>
                <b><code>letter</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="letter"
               data-endpoint="GETapi-child-categories--slug---letter--"
               value="voluptatem"
               data-component="url" hidden>
    <br>

            </p>
                    </form>

            <h2 id="endpoints-GETapi-get-category-stores--slug-">GET api/get-category-stores/{slug}</h2>

<p>
</p>



<span id="example-requests-GETapi-get-category-stores--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/get-category-stores/est" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/get-category-stores/est"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-get-category-stores--slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 52
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;No Category found&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-get-category-stores--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-get-category-stores--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-get-category-stores--slug-"></code></pre>
</span>
<span id="execution-error-GETapi-get-category-stores--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-get-category-stores--slug-"></code></pre>
</span>
<form id="form-GETapi-get-category-stores--slug-" data-method="GET"
      data-path="api/get-category-stores/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-get-category-stores--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-get-category-stores--slug-"
                    onclick="tryItOut('GETapi-get-category-stores--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-get-category-stores--slug-"
                    onclick="cancelTryOut('GETapi-get-category-stores--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-get-category-stores--slug-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/get-category-stores/{slug}</code></b>
        </p>
                    <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <p>
                <b><code>slug</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="slug"
               data-endpoint="GETapi-get-category-stores--slug-"
               value="est"
               data-component="url" hidden>
    <br>
<p>The slug of the get category store.</p>
            </p>
                    </form>

            <h2 id="endpoints-POSTapi-exit-click">POST api/exit-click</h2>

<p>
</p>



<span id="example-requests-POSTapi-exit-click">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/exit-click" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/exit-click"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-exit-click">
</span>
<span id="execution-results-POSTapi-exit-click" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-exit-click"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-exit-click"></code></pre>
</span>
<span id="execution-error-POSTapi-exit-click" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-exit-click"></code></pre>
</span>
<form id="form-POSTapi-exit-click" data-method="POST"
      data-path="api/exit-click"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-exit-click', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-exit-click"
                    onclick="tryItOut('POSTapi-exit-click');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-exit-click"
                    onclick="cancelTryOut('POSTapi-exit-click');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-exit-click" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/exit-click</code></b>
        </p>
                    </form>

    

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
