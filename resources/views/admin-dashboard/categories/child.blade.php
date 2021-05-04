<ul>
    @foreach($childs as $child)
        <li>
            <span class="float-right">
                <a href="" cashback-id='' class='cashback-edit'><em class="icon ni ni-edit"></em></a>
                <a class='cashback-edi'> <em class="icon ni ni-trash-fill"></em></a>
            </span>
            <em class="icon ni ni-db-fill text-success"></em>  {{ $child->name}}
            
        @if(count($child->childs))
                @include('manageChild',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
    </ul>