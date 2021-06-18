<ul>
    @foreach($childs as $child)
        <li class="border-top   py-1">
            <span class="float-right">
                <div class="actions">
                    <div class="drodown d-inline">
                        <a href="#" class="dropdown-toggle badge badge-info text-white" data-toggle="dropdown"><em class="icon ni ni-plus mr-1"></em>Options</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr d-block ml-0">
                                <a href="{{route('admin.categories.picks',$child)}}" category-id='{{$child->id}}' class='picks-edit'  ><em class="icon ni ni-cart-fill"></em> Editor Picks</a>
                                <a href="{{route('admin.categories.edit',$child)}}" category-id='{{$child->id}}' class='category-edit' ><em class="icon ni ni-edit"></em> Edit</a>
                                <a  onclick="$('#delete-form-{{$child->id}}').submit();"  style="cursor: pointer"  > <em class="icon ni ni-trash-fill"></em> Delete</a>
                            </ul>
                        </div>
                    </div>        
                </div>                                                     
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