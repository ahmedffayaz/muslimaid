<ul>
    @foreach($childs as $child)
        <li>
            <span class="float-right">
                <a href="{{route('admin.categories.edit',$child)}}" category-id='{{$child->id}}' class='category-edit' ><em class="icon ni ni-edit text-primary"></em></a>
                <a  onclick="$('#delete-form-{{$child->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill text-danger"></em></a>
                                                    
                <form action="{{ route('admin.categories.destroy', $child) }}" id="delete-form-{{$child->id}}" method="POST" class="m-0">
                    @method('DELETE')
                    @csrf
                    
                </form>
            </span>
            <em class="icon ni ni-db-fill text-success"></em>  {{ $child->name}}
            
        @if(count($child->childs))
                @include('admin-dashboard.categories.child',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
    </ul>