@php
    $color = $settings['theme_color'] ? $settings['theme_color'] : '#47991f';
    $primarycolor = $color;
@endphp

<style>
    .color-primary {
        color: {{ $primarycolor }};
    }

    .fill-primary {
        fill: {{ $primarycolor }};
    }

    a {
        color: {{ $primarycolor }};
    }

    a:hover {
        color: {{ $primarycolor }};
        text-decoration: none;
    }

    @media (min-width: 992px) {
        [dir=ltr] .account-nav__item--active a {
            border-left: 2px solid {{ $primarycolor }};
        }

        [dir=rtl] .account-nav__item--active a {
            border-right: 2px solid {{ $primarycolor }};
        }
    }

    @media (max-width: 991px) {
        .account-nav__item--active a {
            border-color: {{ $primarycolor }};
        }
    }

    .block-header__group--active,
    .block-header__group--active:focus,
    .block-header__group--active:hover {
        -webkit-box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
        box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
    }

    .block-header__arrow:focus,
    .block-header__arrow:hover {
        background: {{ $primarycolor }};
        fill: #fff;
    }

    .block-header__arrow:active {
        background: #547ed4;
        fill: #fff;
    }

    .breadcrumb-item a:hover {
        color: {{ $primarycolor }};
    }

    .btn-primary,
    .btn-primary.disabled,
    .btn-primary:disabled {
        border-color: {{ $primarycolor }};
        background: {{ $primarycolor }};
        color: #fff;
        fill: #fff;
    }

    .btn-primary .fake-svg-icon,
    .btn-primary.disabled .fake-svg-icon,
    .btn-primary:disabled .fake-svg-icon {
        color: #fff;
    }

    .btn-primary.btn-loading:not(:disabled):not(.disabled),
    .btn-primary.btn-loading:not(:disabled):not(.disabled):active,
    .btn-primary.btn-loading:not(:disabled):not(.disabled).active {
        cursor: default;
        border-color: {{ $primarycolor }};
        background: {{ $primarycolor }};
    }

    .filters-button__counter {
        background: {{ $primarycolor }};
        color: #fff;
    }

    .input-check__input:checked~.input-check__box {
        background: {{ $primarycolor }};
    }

    .input-check__input:checked~.input-check__icon {
        fill: #fff;
    }

    .input-radio__input:checked~.input-radio__circle {
        background: {{ $primarycolor }};
    }

    .input-radio__input:checked~.input-radio__circle::after {
        background: #fff;
    }

    .input-radio-label__list input:checked~span {
        background: {{ $primarycolor }};
        color: #fff;
    }

    .layout-switcher__button--active,
    .layout-switcher__button--active:hover {
        border-color: {{ $primarycolor }};
        background: {{ $primarycolor }};
        fill: #fff;
    }

    .noUi-connect {
        background: {{ $primarycolor }};
    }

    .noUi-horizontal .noUi-handle {
        background: {{ $primarycolor }};
    }

    .noUi-horizontal .noUi-handle:after {
        background: #fff;
    }

    .noUi-horizontal .noUi-handle:focus {
        -webkit-box-shadow: 0 0 0 3px rgba(51, 102, 204, 0.3);
        box-shadow: 0 0 0 3px rgba(51, 102, 204, 0.3);
    }

    .page-item.active .page-link {
        background: {{ $primarycolor }};
        color: #fff;
    }

    .post-card__category a:hover {
        color: {{ $primarycolor }};
    }

    .post-card__name a:hover {
        color: {{ $primarycolor }};
    }

    .post-card--layout--related .post-card__name a:hover {
        color: {{ $primarycolor }};
    }

    .product-card:hover::before {
        -webkit-box-shadow: 0 0 0 2px #e5e5e5 inset;
        box-shadow: 0 0 0 2px #e5e5e5 inset;
    }

    .product-card:hover .product-card__quickview {
        background: #e5e5e5;
    }

    .product-card__name a:hover {
        color: {{ $primarycolor }};
    }

    .tags__list a:focus,
    .tags__list a:hover {
        background: {{ $primarycolor }};
        border: 1px solid {{ $primarycolor }};
        color: #fff;
    }

    .filter-categories__list a:hover {
        color: {{ $primarycolor }};
    }

    .filter-categories-alt__list a:hover {
        color: {{ $primarycolor }};
    }

    .departments {
        color: #3d464d;
    }

    .departments__button-icon {
        fill: rgba(0, 0, 0, 0.3);
    }

    .departments__button-arrow {
        fill: rgba(0, 0, 0, 0.3);
    }

    .departments__body {
        background: #fff;
        -webkit-box-shadow: 0 0 0 2px {{ $primarycolor }};
        box-shadow: 0 0 0 2px {{ $primarycolor }};
    }

    .departments__item--hover .departments__item-link {
        background: rgba(0, 0, 0, 0.05);
    }

    .departments__item-arrow {
        fill: rgba(0, 0, 0, 0.3);
    }

    .dropcart__product-name a:hover {
        color: {{ $primarycolor }};
    }

    .indicator--open .indicator__area,
    .indicator--hover .indicator__area {
        background: rgba(255, 255, 255, 0.15);
    }

    .megamenu__links a:hover {
        color: {{ $primarycolor }};
    }

    .megamenu__links--level--1>.megamenu__item>a:hover {
        color: {{ $primarycolor }};
    }

    .mobile-header__panel {
        background: {{ $primarycolor }};
        color: #fff;
    }

    .mobile-header__menu-button {
        fill: #fff;
    }

    .mobile-header__menu-button:focus,
    .mobile-header__menu-button:hover {
        background: rgba(255, 255, 255, 0.15);
        fill: #fff;
    }

    .nav-links__item--has-submenu .nav-links__item-arrow {
        fill: rgba(0, 0, 0, 0.25);
    }

    .nav-panel {
        height: 54px;
    }

    .nav-panel__logo svg {
        fill: #fff;
    }

    .search--location--header .search__categories {
        background-color .2s;
    }

    .search--location--header .search__input {
        color: #3d464d;
    }

    .search--location--header .search__input~.search__border {
        background: #fff;
    }

    .search--location--header .search__input:hover {
        color: #3d464d;
    }

    .search--location--header .search__input:hover~.search__border {
        background: #fff;
    }

    .search--location--header .search__input:hover~.search__button {
        fill: #bfbfbf;
    }

    .search--location--header .search__input:hover~.search__button:hover {
        fill: {{ $primarycolor }};
    }

    .search--location--header .search__input:focus,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input {
        outline: none;
        color: #3d464d;
    }

    .search--location--header .search__input:focus::-webkit-input-placeholder,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input::-webkit-input-placeholder {
        color: #999;
    }

    .search--location--header .search__input:focus::-moz-placeholder,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input::-moz-placeholder {
        color: #999;
    }

    .search--location--header .search__input:focus:-ms-input-placeholder,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input:-ms-input-placeholder {
        color: #999;
    }

    .search--location--header .search__input:focus::-ms-input-placeholder,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input::-ms-input-placeholder {
        color: #999;
    }

    .search--location--header .search__input:focus::placeholder,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input::placeholder {
        color: #999;
    }

    .search--location--header .search__input:focus~.search__border,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input~.search__border {
        background: #fff;
    }

    .search--location--header .search__input:focus~.search__button,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input~.search__button {
        fill: #bfbfbf;
    }

    .search--location--header .search__input:focus~.search__button:hover,
    .search--location--header.search--has-suggestions.search--suggestions-open .search__input~.search__button:hover {
        fill: {{ $primarycolor }};
    }

    .search--location--header .search__button:hover,
    .search--location--header .search__button:focus {
        fill: {{ $primarycolor }};
    }

    .search--location--mobile-header.search--has-suggestions.search--suggestions-open .search__input,
    .search--location--mobile-header .search__input:focus {
        color: #3d464d;
    }

    .search--location--mobile-header.search--has-suggestions.search--suggestions-open .search__button,
    .search--location--mobile-header .search__input:focus~.search__button {
        fill: #b3b3b3;
    }

    .site-header {
        background: {{ $primarycolor }};
        color: #fff;
    }

    .site-header__phone-title {
        color: #99bbff;
    }

    .topbar {
        background: {{ $primarycolor }};
        border-bottom: 1px solid #2e5cb8;
        color: #99bbff;
    }

    .topbar__item-value {
        color: #fff;
    }

    .topbar-dropdown--opened .topbar-dropdown__btn,
    .topbar-dropdown__btn:hover,
    .topbar-dropdown__btn:focus {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        fill: rgba(255, 255, 255, 0.4);
    }

    .topbar-dropdown--opened .topbar-dropdown__btn .topbar__item-value,
    .topbar-dropdown__btn:hover .topbar__item-value,
    .topbar-dropdown__btn:focus .topbar__item-value {
        color: #fff;
    }

    .topbar-link:hover {
        color: #fff;
    }

    .footer-links__list a:hover {
        color: {{ $primarycolor }};
    }

    .totop__button {
        color: #fff;
        background: {{ $primarycolor }};
    }

    .teammates .owl-carousel .owl-dot.active {
        color: {{ $primarycolor }};
    }

    .address-card__badge {
        background-color: {{ $primarycolor }};
        color: #fff;
    }

    .category-card__name a:hover {
        color: {{ $primarycolor }};
    }

    .category-card__links a:hover {
        color: {{ $primarycolor }};
    }

    .order-success__icon {
        fill: {{ $primarycolor }};
    }

    .product__rating-legend a:hover {
        color: {{ $primarycolor }};
    }

    .product__meta a:hover {
        color: {{ $primarycolor }};
    }

    .product-gallery__carousel-item--active {
        -webkit-box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
        box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
    }

    .product-tabs__item--active,
    .product-tabs__item--active:hover {
        border-bottom-color: {{ $primarycolor }};
    }

    .comment__author a:hover {
        color: {{ $primarycolor }};
    }

    .post-header__meta a:hover {
        color: {{ $primarycolor }};
    }

    .block--highlighted {
        background: #f0f8ff;
    }

    .block-features__icon {
        fill: {{ $primarycolor }};
    }

    .block-slideshow .owl-carousel .owl-dot.active {
        background: {{ $primarycolor }};
    }

    .widget-categories__row a:hover {
        color: {{ $primarycolor }};
    }

    .widget-categories__subs a:hover {
        color: {{ $primarycolor }};
    }

    .widget-comments a:hover {
        color: {{ $primarycolor }};
    }

    .widget-comments__author a:hover {
        border-color: rgba(51, 102, 204, 0.8);
    }

    .widget-posts__name a:hover {
        color: {{ $primarycolor }};
    }

    .widget-products__name a:hover {
        color: {{ $primarycolor }};
    }

    .widget-search__input:focus {
        -webkit-box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
        box-shadow: 0 0 0 2px {{ $primarycolor }} inset;
    }

    .widget-search__button:focus,
    .widget-search__button:hover {
        fill: {{ $primarycolor }};
    }

    .coupon-button-type .coupon-code .get-code {
        background: {{ $primarycolor }};
    }

    .coupon-button-type .coupon-code .get-code:after {
        border-left: 45px solid {{ $primarycolor }};
    }

    .coupon-button-type .coupon-code .get-code:after {
        border-left-color: {{ $primarycolor }};
    }

    .see-all-vouchers,
    .see-all-vouchers:hover,
    .see-all-cashback,
    .see-all-cashback:hover {
        color: {{ $primarycolor }};
    }
</style>
