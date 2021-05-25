@foreach($childs as $child)
                                    
        <option 
                @if($isEdit) @if($category->mapped_to == $child->id) selected @endif  @endif
                 value="{{$child->id}}">{{$dashes}}{{$child->name}}
        </option>

        @if(count($child->childs))
        @php $newdashes = $dashes.'~'; @endphp
                @if($isEdit)
                        @include('admin-dashboard.imported-categories.child_input',['childs' => $child->childs, 'category'=>$category,'dashes'=>$newdashes])
                @else
                        @include('admin-dashboard.imported-categories.child_input',['childs' => $child->childs,'dashes'=>$newdashes])
                @endif
        @endif

    @endforeach
