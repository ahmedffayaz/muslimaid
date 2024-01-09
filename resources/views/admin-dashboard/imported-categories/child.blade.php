<ul>
    @foreach($childs as $child)
        <li>
            <div class="row">
                <div class="col-md-4">
            <em class="icon ni ni-db-fill text-success"></em>  {{ $child->name}}

                </div>
                <div class="col-md-4 text-center">
                    {{ $child->mappedTo->name ?? ''}}
                </div>
                <div class="col-md-4">
                    <span class="float-right">
                        <a href="{{route(getAdminPrefix() . '.categories.edit',$child)}}" category-id='{{$child->id}}' class='category-edit' ><em class="icon ni ni-edit text-primary"></em></a>
                        <a  onclick="$('#delete-form-{{$child->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill text-danger"></em></a>
                                                    
                        <form action="{{ route(getAdminPrefix() . '.importedcategories.destroy', $child) }}" id="delete-form-{{$child->id}}" method="POST" class="m-0">
                            @method('DELETE')
                            @csrf
                            
                        </form>
                    </span>
                </div>
            </div>
            
            
        @if(count($child->childs))
                @include('admin-dashboard.categories.child',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
    </ul>