<!doctype html>
@php
    $page = getPageTemplates('maintenance');
    if (isset($page)) {
        $keywords = separatePageKeywords($page->lb_content);
    }
@endphp
<!doctype html>
<title>{{ isset($page) ? $page->title : 'Site Maintenance' }}</title>
<meta name="description" content="{{ isset($page) ? $page->meta_description : 'Site Maintenance' }}">
<meta name="keywords" content="{{ isset($page) ? $page->meta_keyword : 'Site Maintenance' }}">
<meta name="title" content="{{ isset($page) ? $page->meta_title : 'Site Maintenance' }}">
<style>
    body {
        text-align: center;
        padding: 50px;
    }

    h1 {
        font-size: 50px;
    }

    body {
        font: 20px Helvetica, sans-serif;
        color: #333;
    }

    article {
        display: block;
        text-align: center;
        width: 650px;
        margin: 0 auto;
    }

    a {
        color: #dc8100;
        text-decoration: none;
    }

    a:hover {
        color: #333;
        text-decoration: none;
    }

    img {
        max-width: 400px;
    }
</style>

<article>
    @if (isset($keywords[0]) && $keywords[0] != '')
        @foreach ($keywords as $keyword)
            @include('errors.partial.503_partial', ['keyword' => $keyword])
        @endforeach
    @else
        <img src="{{ asset('admin-dashboard/images/Maintenance-bro.png') }}" alt="503">
        <h1>We&rsquo;ll be back soon!</h1>
        <div>
            <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. We&rsquo;ll be back online shortly!</p>
        </div>
    @endif
</article>
