<div class="widget-categories__subs" data-collapse-content>
    <ul>
        @foreach ($childs as $cat)
        <li><a href="{{route('cashabck',$cat->slug)}}">{{$cat->name}}</a>
        </li>
        
        @endforeach
        
       
    </ul>
</div>