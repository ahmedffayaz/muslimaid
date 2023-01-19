@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <div class="page-header__breadcrumb ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Home</a>
                        <svg class="breadcrumb-arrow" width="6px" height="9px">
                            <use xlink:href="{{ asset('frontend/images/sprite.svg') }}#arrow-rounded-right-6x9"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container p-2 my-2">
        <div class="row">
            <div class="col-12">
                <div class="block-finder__body">
                    <img class="banner__size" src="{{ $page->banner_image }}" alt="{{ $page->title }} Image Missing">
                </div>
            </div>
        </div>
    </div>

    <div class="block faq">
        <div class="container">
            <div class="faq__section">
                <div class="faq__section-title">
                    <h3>General Queries</h3>
                </div>
                <div class="faq__section-body">
                    <div class="row">
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>What is Cashback?</h6>
                                <p>
                                    Cashback is a platform that provides cashback rewards to our members when they shop online with Black-owned businesses.
                                    Register with us, browse for products and services and when you make a purchase, we'll reward you with cashback from affiliate
                                    commissions we receive from
                                </p>
                                <h6>How does cashback work?</h6>
                                <p>
                                    When you make a purchase from a retailer on Cashback, the retailer pays us an affiliate commission on the sale made through us.
                                    We then make your cashback 'payable' for you to withdraw or transfer it to your chosen cause.
                                </p>
                                <h6>On what purchases can I earn cashback?</h6>
                                <p>
                                    You can earn cashback on a wide range of goods and services from the Black-owned retailers on our platform. Many types of consumer goods,
                                    services and even food delivery purchases through your favourite delivery apps.
                                </p>
                            </div>
                        </div>
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>Do I need an account to use Cashback?</h6>
                                <p>
                                    Yes. In order for us to track your purchases and give you cashback, you must be signed in to Cashback and click our link to the store before
                                    every purchase.
                                </p>
                                <h6>Am I buying directly from Cashback?</h6>
                                <p>No. Cashblack is not a store and does not sell any products</p>
                                <p>Cashblack is an online shopping platform which only helps generate cashback rewards for our members.</p>
                                <p>If you have a question about the products you ordered, please contact the store you purchased from directly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq__section">
                <div class="faq__section-title">
                    <h3>Payment Queries</h3>
                </div>
                <div class="faq__section-body">
                    <div class="row">
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>WHow do I withdraw my cashback?</h6>
                                <p>
                                    Providing you have some transactions which have become 'Payable', you can request a payout at any time.
                                </p>

                                <h6>Do I need to pay any fee for my payment?</h6>
                                <p>No. There are no fees for your payment.</p>
                                <p>You will receive all the money that you earn on Cashblack.</p>
                            </div>
                        </div>
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>When can I request a payment?</h6>
                                <p>When your account's available cashback amount reaches a minimum of £10, you can request a payment.</p>
                                <h6>Why is there a wait before I receive my cashback?</h6>
                                <p>
                                    The merchants get invoiced for the cashback after the end of the calendar month in which the transaction occurred. This means that if your
                                    transaction was at the start of a month, then you will probably be waiting a longer time for your cashback than someone who purchased at the
                                    end of a month.
                                </p>
                                <p>
                                    After the invoice has been sent, it can take up to 30 to 60 days for the cashback to be paid to the affiliate network and for it to then be sent
                                    on to us.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq__section">
                <div class="faq__section-title">
                    <h3>DO's and DON'Ts</h3>
                </div>
                <div class="faq__section-body">
                    <div class="row">
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>Is enabling cookies on my browser important?</h6>
                                <p>
                                    DO enable cookies on your browser while you are shopping. Merchants use cookies to track your purchases so you can get cashback rewards from
                                    Cashblack.
                                </p>
                                <p>
                                    Remember, if the merchant doesn't track your purchase, Cashblack does not receive an affiliate commission which means we can't pay you the
                                    cashback.
                                </p>
                                <h6>Is emptying my previous shopping cart important?</h6>
                                <p>
                                    DO make sure your shopping cart is empty when you click to the merchant's website from Cashback. In order to earn a Cashback donation, some
                                    merchants require that your shopping cart is empty when you click from Cashback to the merchant's website. Some merchants keep track of when
                                    you put items in your shopping cart and if you don't do it after you clicked from Cashback, it's possible that you won't earn cashback rewards.
                                </p>
                                <h6>What if I accidentally close the window</h6>
                                <p>
                                    DON'T close the store website while shopping. If you do close the window, you must go back to the Cashback merchant page and click from Cashback
                                    to start a new session. 
                                </p>
                                <h6>Is there anything I need to do to make sure my cashback is tracked</h6>
                                <p>
                                    If you have any other shopping oriented toolbars or browser add-ons installed (Honey, Top Cashback etc), they can interfere with the proper
                                    functioning of Cashback and, therefore, earning Cashback. For best results, we recommend removing any other shopping oriented browser add-ons
                                    or toolbars. 
                                </p>
                            </div>
                        </div>
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>Am I still going to earn cashback if I shop directly from merchant?</h6>
                                <p>
                                    DO click to visit the merchant's website from Cashback. Clicking to Cashback from other websites or typing the merchant's website directly into
                                    your web browser without going through Cashback will invalidate your cashback. Make sure that you don't click any other links to the merchant's
                                    website after clicking through from Cashback.
                                </p>

                                <h6>Can I delete site cookies while shopping?</h6>
                                <p>
                                    DON'T delete cookies while you are shopping. We know that some people occasionally delete their browser cookies. Unfortunately, if you happen to
                                    delete cookies while in the middle of a Cashback shopping session, the cashback will not be tracked. Wait until after all of your shopping is
                                    complete before you consider deleting cookies. 
                                </p>
                                <h6>Can I checkout multiple times?</h6>
                                <p>
                                    DON'T checkout multiple times at an online merchant without clicking back through Cashback. If you want to keep shopping after checking out the
                                    first time, you must go back to Cashback and then click back to the merchant again. Even if you are ordering the same item at the same merchant
                                    but in a new order, you need to have a new record of clicking through to the merchant to go with your new order. So after every checkout, go
                                    back to Cashback to start again and click back to the same store or select a new store.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq__section">
                <div class="faq__section-title">
                    <h3>Other Cashback Queries</h3>
                </div>
                <div class="faq__section-body">
                    <div class="row">
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>Can I add an order inquiry if I didn't use Cashback to click to the website I ordered from</h6>
                                <p>
                                    No. The merchant will track your orders via cookies. We will also track all the records. If you didn't first click the link to shop from
                                    Cashback , it means you haven't ordered using Cashback. Please click from Cashback for every order, even when you need to order several times
                                    with one merchant. 
                                </p>
                                <h6>Can I use or buy a gift card and still get cash back</h6>
                                <p>
                                    Sometimes. While it's not guaranteed, you can sometimes earn cashback when you buy a gift card. Check the special terms to see if using a gift
                                    card to make the purchase is allowed. If the order is not confirmed in 7 days, it may not have been allowed. 
                                </p>
                            </div>
                        </div>
                        <div class="faq__section-column col-12 col-lg-6">
                            <div class="typography">
                                <h6>Why has my cashback been declined</h6>
                                <p>
                                    If the cashback amount has been crossed out on one of your transactions, and it is showing in the 'declined' column of your Earnings page, this
                                    means that the merchant has marked your transaction as being ineligible for cashback. It may also say Payable at £0.00
                                </p>
                                <p>We may not know exactly why the merchant has reached this decision, but some possible reasons may include:</p>
                                <p>
                                    - You cancelled or returned your order or part of your order
                                    <br>
                                    - You used a promotional code, voucher code or another form of discount (student discount, NHS discount etc) not approved by Cashblack when
                                    making this purchase
                                    <br>
                                    - You asked for a quote or browsed a merchant's website, but didn't make a purchase
                                    <br>
                                    - You may have used a saved quote or a renewal quote to make this purchase
                                    <br>
                                    - The merchant pays cashback to new customers only, and you have shopped with this merchant before (please see the merchant's page to see
                                    whether this applies)
                                    <br>
                                    - You did not complete your order online i.e. it was finalised over the phone
                                    <br>
                                    - You returned part of your order
                                    <br>
                                    - You did not meet any other of the Terms and Conditions set by the merchant
                                    <br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
