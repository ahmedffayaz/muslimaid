@if (strpos($keyword, '_KEYWORD'))
    @php $keyword = strtolower($keyword); @endphp
    @if ($keyword == 'image_keyword')
        <img src="{{ asset('admin-dashboard/images/Maintenance-bro.png') }}" alt="503">
    @elseif($keyword == 'link_keyword')
        <a href="/">Homepage</a>
    @else
        {!! $keyword !!}
    @endif
@else
    {!! $keyword !!}
@endif
