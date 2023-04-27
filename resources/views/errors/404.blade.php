@php
    $page = getPageTemplates('404');
    if(isset($page)){
      $keywords = separatePageKeywords($page->lb_content);
    }
@endphp
<!doctype html>
<title>{{ isset($page) ? $page->title : 'Error 404' }}</title>
<meta name="description" content="{{ isset($page) ? $page->meta_description : 'Error 404' }}">
<meta name="keywords" content="{{ isset($page) ? $page->meta_keyword : 'Error 404' }}">
<meta name="title" content="{{ isset($page) ? $page->meta_title : 'Error 404' }}">
<style>
    body {
        text-align: center;
        padding: 40px;
    }

    h1 {
        font-size: 50px;
        margin-block-start: 0%
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
        color: #407BFF;
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
    {!! resolvePageShortCodes($page->lb_content, $__data) !!}
</article>