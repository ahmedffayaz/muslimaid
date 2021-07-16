<div class="nav-panel__departments">
    <!-- .departments -->
    <div class="departments " data-departments-fixed-by="">
        <div class="departments__body">
            <div class="departments__links-wrapper">
                <div class="departments__submenus-container"></div>
                <ul class="departments__links">
                    @php $categories = getCategories(); @endphp
                    @foreach ($categories as $category)
                       
                  
                    <li class="departments__item">
                        <a class="departments__item-link text-capitalize" href="{{route('cashabck',$category->slug)}}">
                            {{$category->name}}
                            @if(count($category->childs))
                            <svg class="departments__item-arrow" width="6px" height="9px">
                                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                            </svg>
                            @endif
                        </a>
                        @if(count($category->childs))
                            @include('frontend.components.subcategories-dropdown',['childs' => $category->childs])
                        @endif
                        
                        
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button class="departments__button">
            <svg class="departments__button-icon" width="18px" height="14px">
                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#menu-18x14"></use>
            </svg>
            Shop By Category
            <svg class="departments__button-arrow" width="9px" height="6px">
                <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-down-9x6"></use>
            </svg>
        </button>
    </div>
    <!-- .departments / end -->
</div>