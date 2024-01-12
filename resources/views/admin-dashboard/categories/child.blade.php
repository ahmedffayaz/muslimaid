
<ul>
    @foreach ($childs->sortBy(['sort', 'name']) as $child)
        <li class="border-top   py-1">
            <span class="float-right">
                <div class="actions">
                    <div class="drodown d-inline">
                        <a href="#" class="dropdown-toggle badge badge-info text-white" data-toggle="dropdown"><em
                                class="icon ni ni-plus mr-1"></em>Options</a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr d-block ml-0">
                                <a href="{{ route(getAdminPrefix() . '.categories.picks', $child) }}" category-id='{{ $child->id }}'
                                    class='picks-edit'><em class="icon ni ni-cart-fill"></em> Editor Picks</a>
                                <a href="{{ route(getAdminPrefix() . '.categories.edit', $child) }}" category-id='{{ $child->id }}'
                                    class='category-edit'><em class="icon ni ni-edit"></em> Edit</a>
                                <a class='category-delete' data-action="{{ route(getAdminPrefix() . '.categories.destroy', $child) }}"
                                    data-id="{{ $child->id }}" style="cursor: pointer"> <em
                                        class="icon ni ni-trash-fill"></em> Delete</a>
                            </ul>
                        </div>
                    </div>
                </div>
            </span>
            <em class="icon ni ni-db-fill text-success"></em> {{ $child->name }}
            <span class="ml-1">
                @if ($child->picks->count())
                    <span class="badge badge-dim badge-pill badge-primary text-capitalize">
                        <em class="icon ni ni-done"></em> Editor Picks
                    </span>
                @endif
            </span>
            <span class="badge badge-dim badge-pill badge-primary ml-1">Total Stores: {{ $child->stores_count }}</span>
            <span class="ml-1">
                {!! $child->enableGoogleMap() !!}
            </span>
            @if (count($child->childs))
                @include('admin-dashboard.categories.child', ['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
