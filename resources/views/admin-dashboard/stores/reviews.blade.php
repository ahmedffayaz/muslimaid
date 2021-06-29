{{-- <a href="#add-review-modal" class="btn btn-primary float-right btn-sm" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Review</span></a> --}}
<div class="card mt-3">
    @if(count($store->reviews))
        @foreach ($store->reviews as $review)
            <div class="card-inner">
                <div class="between-center flex-wrap flex-md-nowrap g-3">
                    <div class="nk-block-text">
                        <h6>{{$review->reviewer}} &nbsp;  {!! $review->status =='active'  ? '<span class="badge badge-success ml-0">Active</span>' : '<span class="badge badge-danger ml-0">Inactive</span>'!!}</h6>
                        <p>{!!$review->review!!} </p>
                    </div>
                    <div class="nk-block-actions">
                        <a href='' review-id='{{$review->id}}' class="btn btn-primary btn-sm review-edit" ><em class="icon ni ni-edit"></em><span>Edit</span></a>
                        
                    </div>
                </div>
            </div>
                <!-- Modal Form -->

        @endforeach
    @else
        <p>No reviews found</p>
    @endif
</div>