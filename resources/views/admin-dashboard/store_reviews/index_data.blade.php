<style>
    .nk-tb-list{
        table-layout: fixed;
    }
</style>
@if(count($reviews))
<div class="nk-tb-item nk-tb-head">



    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Reviwer</span></div>
    <div class="nk-tb-col tb-col-mb" style="width:60%"><span class="sub-text">Review</span></div>
    <div class="nk-tb-col "><span class="sub-text">Store</span></div>
    <div class="nk-tb-col tb-col-mb text-center"><span class="sub-text">Status</span></div>
    {{-- <div class="nk-tb-col "><span class="sub-text">Status</span></div> --}}
    <div class="nk-tb-col nk-tb-col-tools text-right">
        <span class="sub-text">Action</span>

    </div>
</div><!-- .nk-tb-item -->

@foreach ($reviews as $review)
<div class="nk-tb-item">
    <div class="nk-tb-col">
        <span><a href="{{route('admin.reviews.edit', $review)}}" class="review-edit text-dark @if($review->status != 'active') icon-status icon-status-info @endif" review-id={{$review->id}}>{{$review->user->first_name . ' ' . $review->user->last_name}}</a></span>
    </div>

    <div class="nk-tb-col">
        <span><a href="{{route('admin.reviews.edit', $review)}}" class="review-edit a_link" review-id={{$review->id}}>{!!$review->review!!}</a></span>
    </div>
    <div class="nk-tb-col"><a href="{{route('admin.stores.show_store')}}?slug={{$review->store->slug}}" class="a_link">
        <span><b>{{$review->store->id ?? ''}} - {{$review->store->name ?? ''}}</b></span><br>
        <span>{{$review->store->network->name ?? ''}}</span></a>
    </div>
    <div class="nk-tb-col text-center">
        <span>        {!! $review->status =='active'  ? '<span class="tb-status badge badge-success">active</span>' : '<span class="tb-status badge badge-danger">pending</span>'!!}
    </span>
    </div>
    <div class="nk-tb-col nk-tb-col-tools">
        <ul class="nk-tb-actions gx-1">

            <li>
                <div class="drodown">
                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{route('admin.reviews.edit', $review)}}" class="review-edit" review-id={{$review->id}}><em class="icon ni ni-edit"></em><span>Edit Review</span></a></li>
                            {{-- <li><a href="{{route('admin.stores.images', $store)}}"><em class="icon ni ni-eye"></em><span>View Store Images</span></a></li> --}}
                            @can('delete reviews')
                                <li><a form_id="delete-review-{{ $review->id }}" class="delete-review" style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em><span>Delete Review</span></a>

                                    <form action="{{ route('admin.reviews.destroy', $review) }}" id="delete-review-{{$review->id}}" method="POST" class="m-0">
                                        @method('DELETE')
                                        @csrf

                                    </form>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div><!-- .nk-tb-item -->

@endforeach

<div class="nk-block-between-md g-3 card-inner">
    <div class="pagination g" route="{{$route}}">
        {!! $reviews->links()!!}

        </div>


</div><!-- .nk-block-between -->


@else
<h3 class="m-auto text-center py-5">No results found</h3>
@endif
