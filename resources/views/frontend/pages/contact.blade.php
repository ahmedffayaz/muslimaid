@extends('layouts.frontend.app')
@section('content')
<div class="container">
    <div class="page-header__breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/')}}">Home</a>
                    <svg class="breadcrumb-arrow" width="6px" height="9px">              
                        <use xlink:href="{{asset('frontend/images/sprite.svg')}}#arrow-rounded-right-6x9"></use>
                    </svg>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container p-1 my-1">
    <div class="row">
        <div class="col-12">
            <div class="block-finder__body">
               <img  class="banner__size" src="{{url('storage/photos/1/del.png')}}" alt="bjh">
                <div class="block-finder__header">
                   <div class="block-finder__title">Contact Us</div>
                   <div class="block-finder__subtitle"></div>
                </div>
            </div>
            <div class="category-text panel  mb-4 mt-1">
                <p class="mt-1">
                    With Cashblack To Your Door, you can discover local restaurants and grocery stores to order African, Caribbean or 
                    from any other authentic Black-owned vendors and earn cashback through our partnerships with your favourite 
                    delivery apps such as Deliveroo and Uber Eats. Whether there’s rice at home or not, you can be sure that through 
                    Cashblack To Your Door, you’ll always be able to find local Black-owned retailers for your next order.
                    You can go to <span class="text-bold text-success"> Grocery Stores</span> or 
                    <span class="text-bold text-success">Restaurants.</span>
                </p>
            </div>
        </div>
    </div>
</div>

@if(Session::has('success'))
<div class="toast bg-success m-2" role="alert" aria-live="assertive" aria-atomic="true"
    style="position:absolute; top:0; right:0; z-index: 200">
    <div class="toast-header p-3">
        <strong class="mr-auto">Thank you!<br>
        Your message has been successfully sent. We will contact you very soon!</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif


<div class="block block-product-columns mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <h1>{{$page->title}}</h1>
                

            </div>
            <div class="col-lg-12">
                <div id="your_container"> <!-- The element you want to render the content in -->
                    {!! $page->lb_content !!}
                  </div>
            </div>
        </div>
    </div>
</div>
<div class="block">
    <div class="container">
    <div class="row py-5" style="background: aliceblue">
        <div class="col-12 col-xl-8 col-md-12 mx-auto">
            <h4 class="contact-us__header card-title">Leave us a Message</h4>
            <form action="{{route('contactForm')}}" method="POST" class="form-validate">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form-name">Your Name</label>
                        <input type="text" id="form-name" name="name" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form-email">Email</label>
                        <input type="email" id="form-email" name="email" class="form-control" placeholder="Email Address" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="form-subject">Subject</label>
                    <input type="text" id="form-subject" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <label for="form-message">Message</label>
                    <textarea id="form-message" class="form-control" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
                
    </div>
</div>
@endsection

@push('scripts')

<script>
    jQuery.validator.addMethod("regex", function(value, element) {
    return this.optional(element) || /^[A-Za-z ]+$/i.test(value);
    }, "Only alphabetic input is allow");
    
    $('.form-validate').validate({
        errorClass: 'invalid-feedback d-block',
        rules: {
            name: {
                required: true,
                regex: true
            },
            email: {
                required: true,
                email: true
            },
            subject: {
                required: true,
                regex: true
            },
            message: {
                required: true,
                minlength: 20
            },
        },
        submitHandler: function(form) {
            if ($(form).valid())
            form.submit();
            return false;
        }
    });
</script>

@endpush