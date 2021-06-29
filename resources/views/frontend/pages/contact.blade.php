@extends('layouts.frontend.app')
@section('content')
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
            <form action="{{route('contactForm')}}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form-name">Your Name</label>
                        <input type="text" id="form-name" name="name" class="form-control" placeholder="Your Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form-email">Email</label>
                        <input type="email" id="form-email" name="email" class="form-control" placeholder="Email Address">
                    </div>
                </div>
                <div class="form-group">
                    <label for="form-subject">Subject</label>
                    <input type="text" id="form-subject" name="subject" class="form-control" placeholder="Subject">
                </div>
                <div class="form-group">
                    <label for="form-message">Message</label>
                    <textarea id="form-message" class="form-control" name="message" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
                
    </div>
</div>
@endsection