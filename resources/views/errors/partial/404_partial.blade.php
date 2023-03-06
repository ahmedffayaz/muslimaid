@if (strpos($keyword, '_KEYWORD'))
    @php $keyword = strtolower($keyword); @endphp
    @if ($keyword == 'image_keyword')
        <img src="{{ asset('frontend/images/404 error.png') }}" alt="">
    @elseif($keyword == 'link_keyword')
        <a href="/">Homepage</a>
    @else
        {!! $keyword !!}
    @endif
@else
    {!! $keyword !!}
@endif
