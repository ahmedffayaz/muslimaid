<style>
    .owl-carousel .owl-item img {
        width: 100px;
    }

    @media (min-width: 768px) {

        .block-slideshow--layout--full .block-slideshow__body,
        .block-slideshow--layout--full .block-slideshow__slide {
            height: 200px;
        }
    }

    @media (max-width: 767px) {

        .block-slideshow__body,
        .block-slideshow__slide {
            height: 226px;
        }
    }

    /* .testimonial */
    .testimonial {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-top: 30px;
    }

    .testimonial__avatar {
        -ms-flex-negative: 0;
        flex-shrink: 0;
    }

    [dir=ltr] .testimonial__avatar {
        margin-left: 16px;
        margin-right: 24px;
    }

    [dir=rtl] .testimonial__avatar {
        margin-right: 16px;
        margin-left: 24px;
    }

    .testimonial__avatar img {
        width: 100px;
        border-radius: 1000px;
    }

    .testimonial__author {
        margin-top: -4px;
        font-size: 16px;
        font-weight: 500;
    }

    .testimonial__position {
        margin-top: 3px;
        font-size: 15px;
        font-weight: 500;
    }

    .testimonial__text {
        font-size: 16px;
        margin-top: 12px;
    }

    @media (min-width: 576px) and (max-width: 767px) {
        [dir=ltr] .testimonial__avatar {
            margin-right: 18px;
        }

        [dir=rtl] .testimonial__avatar {
            margin-left: 18px;
        }

        .testimonial__avatar img {
            width: 60px;
        }
    }

    /* .testimonials-list */
    .testimonials-list__content {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .testimonials-list__item {
        border-bottom: 1px solid #ebebeb;
        padding-top: 28px;
        padding-bottom: 24px;
    }

    .testimonials-list__item:first-child {
        padding-top: 0;
    }
</style>

@if (count($testimonials) > 0)
    <div class="block-slideshow block--highlighted block-slideshow--layout--full block">
        <div class="container">
            <div class="block-header">
                <h3 class="block-header__title">Latest Testimonials</h3>
                <div class="block-header__divider"></div>
            </div>
            <div class="block-slideshow__body">
                <div class="owl-carousel">
                    @foreach ($testimonials as $testimonial)
                        <a class="block-slideshow__slide" href="">
                            <div class="testimonials-view">
                                <div class="testimonials-view__list">
                                    <div class="testimonials-list">
                                        <ol class="testimonials-list__content">
                                            <li class="testimonials-list__item">
                                                <div class="testimonial">
                                                    <div class="testimonial__avatar">
                                                        <img alt="{{ $testimonial->name . ' Avatar' }}"
                                                            @if ($testimonial->image != null && $testimonial->image != '' && file_exists(storage_path('app/public/users/images/avatar/' . $testimonial->image))) 
                                                                src="{{ asset('storage/users/images/avatar/' . $testimonial->image) }}"
                                                            @else
                                                                src="{{ asset('admin-dashboard/images/avatar.png') }}" 
                                                            @endif>
                                                    </div>
                                                    <div class="testimonial__content">
                                                        <div class="testimonial__author">{{ $testimonial->name }}
                                                        </div>
                                                        <div class="testimonial__position">
                                                            {{ $testimonial->position . ', ' . $testimonial->company }}
                                                        </div>
                                                        <div class="testimonial__text">{{ $testimonial->description }}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
