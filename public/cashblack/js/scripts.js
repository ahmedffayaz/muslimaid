$(document).ready(function () {
    $("#request-cashout").appendTo("body");
    $(".image_modal").appendTo("body");
    $(".btn-navbar-close").click(function () {
        $(".navbar-collapse").removeClass("show");
    });

    let toast = $(".toast").toast();
    toast.show();
    setTimeout(function () {
        $(".toast").hide();
    }, 5000);

    $(".toast-close").on("click", function (e) {
        $(".toast").hide();
    });

    $(window).scroll(function () {
        if (
            Math.round(window.scrollY + window.innerHeight) ===
            document.documentElement.scrollHeight
        ) {
            $(".section").addClass("static-sections");
            $(".container").addClass("static-sections");
        }
    });

    $(".acc-after-login").on("click", function (e) {
        console.log("first click");

        if (!$(".sidebar").hasClass("show")) {
            $(".sidebar").addClass("show");
        }
        $(".responsive-menu-overlay").addClass("active");
        e.stopPropagation();
    });

    $(".offcanvas-close, .responsive-menu-overlay").click(function () {
        if ($(".sidebar").hasClass("show")) {
            $(".sidebar").removeClass("show");
        }
        $(".responsive-menu-overlay").removeClass("active");
    });

    $(".owl-carousel").owlCarousel({
        items: 4,
        margin: 20,
        nav: true,
        dots: false,
        autoplay: true,
    });

    $(".carousel-brands").owlCarousel({
        items: 1,
        nav: true,
        dots: false,
        autoplay: true,
    });

    $(".affiliate-donation").owlCarousel({
        autoWidth: true,
        items: 5,
    });

    $(".cb_m_app").owlCarousel({
        items: 1,
        nav: false,
        dots: true,
        autoplay: true,
    });

    $(".datepicker").datepicker({
        format: "mm/dd/yyyy",
        startDate: "-3d",
    });

    // Functionality Points Code ----

    $("form").each(function () {
        $(this).validate();
    });

    $(".cash-img img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") +
                "resources/upload/images/15648842133707348171616775835.jpg"
        );
    });
    $(".cat-missing-icon").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") +
                "resources/front/images/cat-missing-icon.jpg"
        );
    });
    $(".all-cat-main-banner img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") +
                "resources/front/images/categories-banner.png"
        );
    });
    $(".store-cat-main-banner img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") + "resources/front/images/store-cate.jpg"
        );
    });
    $(".store-detail-main-banner img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") +
                "resources/front/images/store-detail-banner.png"
        );
    });
    $(".all-store-banner img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") + "resources/front/images/stores-banner.png"
        );
    });
    $(".all-store-banner img").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") + "resources/front/images/stores-banner.png"
        );
    });
    $(".missing-icon-user").on("error", function () {
        $(this).attr(
            "src",
            $("base").attr("href") + "resources/front/images/profile-icon_.png"
        );
    });

    var clipboard = new ClipboardJS(".copyText");

    $(".lazy").Lazy({
        onError: function (element) {
            console.log(
                element.attr(
                    "src",
                    base_url + "resources/front/images/no-image.png"
                )
            );
        },
    });
    $(".href-popup").magnificPopup({
        type: "ajax",
        // alignTop: true,
        overflowY: "scroll",
    });
    $(".video-popup").magnificPopup({
        disableOn: 700,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
    });
    $(".code[data-store-id]").each(function () {
        $(this).attr("data-clipboard-text", $(this).find("p").text());
        var clipboard = new ClipboardJS(this);
    });
    if ($(".login-form-modal").length > 0) {
        $(".login-form-modal").magnificPopup({
            type: "inline",
            preloader: false,
            closeBtnInside: true,
            tClose: "",
            showCloseBtn: true,
        });
    }
    if ($(".update-cashback-status-modal").length > 0) {
        $(".update-cashback-status-modal").magnificPopup({
            type: "inline",
            preloader: false,
            closeBtnInside: true,
            tClose: "",
            showCloseBtn: true,
        });
    }
    if ($(".register-form-modal").length > 0) {
        $(".register-form-modal").magnificPopup({
            type: "inline",
            preloader: false,
            closeBtnInside: true,
            showCloseBtn: true,
        });
    }
    if ($(".forgot-form-modal").length > 0) {
        $(".forgot-form-modal").magnificPopup({
            type: "inline",
            preloader: false,
            closeBtnInside: true,
            showCloseBtn: true,
        });
    }

    if (getCookie("views") == "lists") {
        $("#listview").trigger("click");
    } else {
        $("#gridview").trigger("click");
    }

    // Helper function for add element box list in WOW
    WOW.prototype.addBox = function (element) {
        this.boxes.push(element);
    };

    // Init WOW.js and get instance
    var wow = new WOW({
        boxClass: "wow", // default
        animateClass: "animate__animated", // default
        offset: 0, // default
        mobile: false, // default
        live: true, // default
    });
    wow.init();

    // Attach scrollSpy to .wow elements for detect view exit events,
    // then reset elements and add again for animation

    $(".wow")
        .on("scrollSpy:exit", function () {
            if ($(window).width() >= 768) {
                $(this)
                    .css({
                        visibility: "hidden",
                        "animation-name": "none",
                    })
                    .removeClass("animate__animated");
            }
            wow.addBox(this);
        })
        .scrollSpy();

    $(".skip-search").click(function () {
        if (!$(".header-search-col").hasClass("show")) {
            $(".header-search-col").addClass("show");
        } else {
            $(".header-search-col").removeClass("show");
        }
    });

    $(".footer-nav__header").click(function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active").siblings().removeClass("active");
        } else {
            $(this).removeClass("active");
        }
    });

    $(".sidebar-cat-menu").click(function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });

    $(".submenu-title").click(function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
        $(".submenu").slideToggle();
    });
});

var base_url = $("base").attr("href");

function getAlertMessages(data = null, form = null) {
    if (data.success) {
        var html =
            '<div class="alert alert-success alert-dismissible">\n' +
            '    <strong><?=lang("lbl_alert_success","Success!")?></strong>\n' +
            '    <span class="messageTextSuccess">' +
            data.message +
            "</span>\n" +
            "</div>";
        /*if ($(form).attr("id") != "login_form"){
            $(form)[0].reset();
        }*/
    } else {
        var html =
            '<div class="alert alert-danger alert-dismissible">\n' +
            '    <strong><?=lang("lbl_alert_error","Error!")?></strong>\n' +
            '    <span class="messageTextError">' +
            data.message +
            "</span>\n" +
            "</div>";
    }
    return html;
}

function reloadTimeOut(givenTime = null, redirect = null) {
    if (givenTime == "") {
        var givenTime = 3000;
    }
    setTimeout(function () {
        if (redirect === null) {
            window.location.reload();
        } else {
            window.location.href = redirect;
        }
    }, givenTime);
}

function subscribe(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/subcribe_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data);
                $("#newsletter_form").find(".messageBox").html(alertMessage);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function register(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/register_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function signupProcess(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/signup_process_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(3000, base_url);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function login(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/login_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                if (data.success) {
                    if (data.user_status == "email_verified") {
                        var redirectUrl =
                            base_url + "client/verify_email/" + data.user_code;
                        console.log(redirectUrl);
                        reloadTimeOut(3000, redirectUrl);
                    } else {
                        reloadTimeOut(3000);
                    }
                }
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function forgot(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/forgot_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function passwordReset(form) {
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/reset_password_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

$(document).on("change", "#add-sort", function () {
    value_sort = this.value;
    if (value_sort != "") {
        window.location.href = $("#get_current_url").val() + value_sort;
    }
});

$(document).on("change", "#add-order", function () {
    value_sort = $("#add-sort").val();
    if (value_sort == "") {
        value_sort = "a-z";
    }
    value_order = this.value;
    if (value_order != "") {
        window.location.href =
            $("#get_current_url").val() + value_sort + "/" + value_order;
    }
});

$(document).on("change", "#cat-sort", function () {
    var cat_sort = $("#cat-sort").val();
    var sort_by = "id";
    var order_by = "desc";
    window.location.href =
        $("#get_current_url").val() + sort_by + "/" + order_by + "/" + cat_sort;
});

$(document).on("change", "#add-sort-stores", function () {
    value_sort = this.value;
    if (value_sort != "Sort By") {
        window.location.href = $("#get_current_url_store").val() + value_sort;
    }
});

$(document).on("change", "#add-order-stores", function () {
    value_sort = $("#add-sort-stores").val();
    if (value_sort == "") {
        value_sort = "a-z";
    }
    value_order = this.value;
    if (value_order != "") {
        window.location.href =
            $("#get_current_url_store").val() + value_sort + "/" + value_order;
    }
});

$(document).on("change", "#filter_by_cat", function () {
    category_id = this.value;
    if (value_sort != "") {
        window.location.href = $("#get_current_url").val() + category_id;
    }
});

$(document).on("click", "[data-store-id]", function () {
    var store_id = $(this).attr("data-store-id");
    var cb = $(this).attr("data-cb");
    var user_id = $("body").data("user-id");
    var parent = $(this).parent();
    var ele = $(this);
    if (user_id > 0) {
        if ($(this).hasClass("showcode")) {
            var enccode = $(this).find("p").text();
            var code = window.atob(enccode);
            $(this).replaceWith(
                $(
                    "<input readonly type='text' data-clipboard-text='" +
                        code +
                        "' value='" +
                        code +
                        "' class='form-control copyText' />"
                )
            );
            parent
                .find("input")
                .notify("Code is copied successfully.", "success");
            var clipboard = new ClipboardJS(parent.find("input")[0]);
            parent.find("input")[0].click();
            clipboard.on("success", function (e) {
                parent
                    .find("input")
                    .notify("Code is copied successfully.", "success");
            });
            setTimeout(function () {
                window.open(
                    base_url +
                        "client/exitclick/" +
                        store_id +
                        "/" +
                        store_id +
                        "/store"
                );
            }, 2000);
        } else {
            if (cb == "door") {
                var cashback_id = $(this).attr("data-cashback-id");
                window.open(
                    base_url +
                        "client/exitclick/" +
                        store_id +
                        "/" +
                        cashback_id +
                        "/cb_door"
                );
            } else {
                window.open(
                    base_url +
                        "client/exitclick/" +
                        store_id +
                        "/" +
                        store_id +
                        "/store"
                );
            }
        }
        $("#visitModal").modal("toggle");
    } else {
        $(".login-form-modal").magnificPopup("open");
    }
});

$("#listview").click(function () {
    if (!$(this).hasClass("active")) {
        $(this).addClass("active");
        $("#gridview").removeClass("active");
        $("#viewType").val("list-view");
    }

    if ($(".category-listing").hasClass("grid-view")) {
        $(".category-listing").removeClass("grid-view");
        $(".category-listing").addClass("list-view");
    }
    setCookie("views", "lists", 365);
});

$("#gridview").click(function () {
    if (!$(this).hasClass("active")) {
        $(this).addClass("active");
        $("#listview").removeClass("active");
        $("#viewType").val("grid-view");
    }

    if ($(".category-listing").hasClass("list-view")) {
        $(".category-listing").removeClass("list-view");
        $(".category-listing").addClass("grid-view");
    }
    setCookie("views", "grids", 365);
});

$(document).on("change", "#stores_par_page", function (e) {
    var data = {
        per_page: $(this).val(),
    };
    var url = base_url + "store/stores_per_page";
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                window.location.reload();
            }
        },
        complete: function (data_response) {},
    });
});

$(document).on("submit", "#updatepic-form", function (e) {
    var form = this;
    e.preventDefault();
    if ($(form).valid() == true) {
        var formData = new FormData(this);
        $.ajax({
            url: base_url + "profile/updatepic_action",
            type: "POST",
            data: formData,
            success: function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(1000);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
});

$(document).on("submit", "#updateprofile-form", function (e) {
    var form = this;
    e.preventDefault();
    if ($(form).valid() == true) {
        var formData = new FormData(this);
        $.ajax({
            url: base_url + "profile/updateprofile_action",
            type: "POST",
            data: formData,
            success: function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(1000);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
});
$(document).on("submit", "#updatepass-form", function (e) {
    var form = this;
    e.preventDefault();
    if ($(form).valid() == true) {
        var formData = new FormData(this);
        $.ajax({
            url: base_url + "profile/updatepass_action",
            type: "POST",
            data: formData,
            success: function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(1000);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
});

function pageone() {
    $("input[name='page']").val(0);
    $("#report-form").submit();
}

function storeAdminPageone() {
    $("input[name='page']").val(0);
    $("#storeadmin-report-form").submit();
}

$(document).on("submit", "#report-form", function (e) {
    var form = this;
    e.preventDefault();
    $("#ajax_result").html("Loading...");
    $.get(
        base_url + "profile/fetch_report",
        $(form).serialize(),
        function (data) {
            $("#ajax_result").html("");
            $("#ajax_result").append(data);
        }
    );
});

$(document).on("submit", "#storeadmin-report-form", function (e) {
    var form = this;
    e.preventDefault();
    $("#ajax_result").html("Loading...");
    $.get(
        base_url + "storeadmin/fetch_report",
        $(form).serialize(),
        function (data) {
            $("#ajax_result").html("");
            $("#ajax_result").append(data);
        }
    );
});

$(document).on("click", ".verify_meeting", function (e) {
    var id = $(this).attr("id");
    var verify_type = $(this).attr("verify_type");
    var ele = $(this);
    $.ajax({
        url: base_url + "profile/verify_meeting",
        type: "POST",
        data: { verify_type: verify_type, id: id },
        success: function (data) {
            data = $.parseJSON(data);
            if (data.success) {
                ele.notify("Updated Successfully!.", "success");
                ele.siblings(".this_val").html(data.new_val);
            }
        },
    });
});

$(document).on("click", ".refererLink", function (e) {
    $("#ref_link").notify("Code is copied successfully.", "success");
});

$(document).on("submit", ".invite-friend", function (e) {
    var form = this;
    e.preventDefault();
    if ($(form).valid() == true) {
        var formData = new FormData(this);
        $.ajax({
            url: base_url + "profile/invite_friend",
            type: "POST",
            data: formData,
            success: function (data) {
                if (data == "success") {
                    $("#multipleEmails").notify(
                        "Invitation email is successfully sent!.",
                        "success"
                    );
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                } else {
                    $("#multipleEmails").notify(
                        "Something goes wrong. Please check your friend's email.",
                        "warn"
                    );
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
});

$(document).on("submit", "#add-ticket", function (e) {
    var form = this;
    e.preventDefault();
    $(form).validate();
    if ($(form).valid() == true) {
        var formData = new FormData(this);
        $("#ajax_result").html("Loading...");
        $.ajax({
            url: base_url + "profile/add_ticket_action",
            type: "POST",
            data: formData,
            success: function (data) {
                var redirectUrl = base_url + "profile/my_tickets";
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(2000, redirectUrl);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    }
});

$(document).on("submit", "#reply-form", function (e) {
    var form = this;
    e.preventDefault();
    var formData = new FormData(this);
    $("#ajax_result").html("Loading...");
    $.ajax({
        url: base_url + "profile/submit_ticket_reply",
        type: "POST",
        data: formData,
        success: function (data) {
            var data = JSON.parse(data);
            var alertMessage = getAlertMessages(data, form);
            $(form).find(".messageBox").html(alertMessage);
            reloadTimeOut(1000);
        },
        cache: false,
        contentType: false,
        processData: false,
    });
});

$(document).on("change", "#add-ticket .ticket_type", function () {
    if ($(this).val() == "Missing Cashback") {
        $(".missing-cashback").show();
    } else {
        $(".missing-cashback").hide();
    }
});

$(document).on("change", "#add-ticket .store_id", function () {
    var store_id = $(this).val();
    if (store_id != "") {
        $(".missing-cashback2").show("");
        $(".exit_click_id").show("");
        $("#add-ticket .exit_click_id option").not(":first").hide();
        $("#add-ticket .exit_click_id option[attr=" + store_id + "]").show();
    } else {
        $("#add-ticket .exit_click_id option").show();
        $(".exit_click_id").hide("");
        $(".missing-cashback2").show("");
    }
    $(".exit_click_id").val("");
});

$(document).on("click", ".like-action", function () {
    var user_id = $("body").data("user-id");

    if (user_id > 0) {
    } else {
        $(".login-form-modal").magnificPopup("open");
        return false;
    }
    var ele = $(this);
    var ajax_url =
        base_url + "profile/like_action/" + ele.attr("this-store-id");
    $.post(ajax_url, {}, function (data) {
        ele.removeClass("not-liked");
        ele.removeClass("liked");
        ele.addClass(JSON.parse(data).class);
        if (JSON.parse(data).removed) {
            $(ele).parents(".li-items").remove();
        }
        ele.notify(JSON.parse(data).message, JSON.parse(data).type);
    });
});

$(document).on("click", ".like-action-remove", function () {
    var user_id = $("body").data("user-id");

    if (user_id > 0) {
    } else {
        $(".login-form-modal").magnificPopup("open");
        return false;
    }
    var ele = $(this);
    var storeTitle = ele.attr("this-title");
    swal({
        title: "Are you sure?",
        text: "Remove " + storeTitle + " from your favourite stores?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var ajax_url =
                base_url + "profile/like_action/" + ele.attr("this-store-id");
            $.post(ajax_url, {}, function (data) {
                ele.removeClass("not-liked");
                ele.removeClass("liked");
                ele.addClass(JSON.parse(data).class);
                if (JSON.parse(data).removed) {
                    $(ele).parents("li").remove();
                }
            });
        } else {
            swal("Your favourite stores list is safe!");
        }
    });
});

function add_review(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "profile/add_edit_review_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                reloadTimeOut(1000);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

function paginationClick(ele) {
    event.preventDefault();
    var page = $(ele).attr("href").split("=")[1];
    if (page >= 0) {
        $("input[name='page']").val(page);
    } else {
        $("input[name='page']").val(0);
    }
    // console.log($(ele));
    $("#report-form").submit();
}

function paginationClickAdmin(ele, adminType) {
    event.preventDefault();
    var page = $(ele).attr("href").split("=")[1];
    if (page >= 0) {
        $("input[name='page']").val(page);
    } else {
        $("input[name='page']").val(0);
    }
    // console.log($(ele));
    if (adminType == "storeadmin") {
        $("#storeadmin-report-form").submit();
    } else {
        $("#report-form").submit();
    }
}

function filterAllCoupons(page_url = "") {
    var store_ids = [];
    $("input:checkbox[name=couponStores]:checked").each(function () {
        store_ids.push($(this).val());
    });
    var filter_by = $(".filter-by").val();
    if (page_url != "") {
        var ajaxUrl = page_url;
    } else {
        var ajaxUrl = base_url + "store/coupon_filter_ajax";
    }
    $.ajax({
        url: ajaxUrl,
        type: "POST",
        data: { store_ids: store_ids, filter_by: filter_by },
        success: function (data) {
            $("#all-coupons-inner").html(data);
            //$('html, body').animate({ scrollTop: 0 }, 'slow');
        },
    });
}

$(document).on("click", ".couponPagination li a", function (event) {
    var page_url = $(this).attr("href");
    if (page_url.indexOf("coupon_filter_ajax") >= 0) {
        event.preventDefault();
        filterAllCoupons(page_url);
    }
});

$(document).on("submit", "#request-cashout-form", function (e) {
    $(".cashoutnow-btn").attr("disabled", true);
    var form = this;
    if ($(form).valid()) {
        $.ajax({
            url: base_url + "profile/cashout_now_action",
            type: "POST",
            data: $(this).serialize(),
            success: function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                if (data.success) {
                    reloadTimeOut(3000);
                }
            },
        });
    }
});

$(document).on("submit", "#request-wallet-form", function (e) {
    var form = this;
    if ($(form).valid()) {
        $.ajax({
            url: base_url + "profile/cashout_wallet_action",
            type: "POST",
            data: $(this).serialize(),
            success: function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                if (data.success) {
                    reloadTimeOut(3000);
                }
            },
        });
    }
});

function addCommentBlog(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $(form).find("[name='submit']").attr("disabled", true);
        $.post(
            base_url + "blog/add_blog_comment",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
            }
        );
    } else {
        // alert("form is not valid");
    }
}

$("#search_btn").on("click", function (e) {
    var search = $("#search_value").val();
    goToSearchPage(search);
});

$("#search_value").jsonSuggest({
    url: base_url + "client/search_ajax",
    maxResults: 20,
    onSelect: callBack,
});

function goToSearchPage(search) {
    // alert('Testing');
    window.location.href = base_url + "store/search?keyword=" + search;
}

function callBack(item) {
    console.warn(item);
    window.location.href = base_url + "store-detail/" + item.id;
}

function callBackCategory(item) {
    console.warn(item);
    window.location.href = base_url + item.id;
}

$(document).on("click", ".star-popup", function () {
    $(".login-form-modal").magnificPopup("open");
});

$(document).on("click", "#refer_friend_tab", function () {
    var user_id = $("body").data("user-id");
    if (user_id > 0) {
        window.location.href = base_url + "profile/refer_and_earn";
    } else {
        $(".login-form-modal").magnificPopup("open");
    }
});

$(document).on("click", "#local-leads", function () {
    var store_id = $(this).attr("leads-store-id");
    var store_cashback_id = $(this).attr("leads-store-cashback-id");
    var user_id = $("body").data("user-id");
    var parent = $(this).parent();
    var ele = $(this);

    if (user_id > 0) {
        window.open(
            base_url +
                "client/leads_generation_meeting/" +
                store_id +
                "/" +
                store_cashback_id
        );
    } else {
        $(".login-form-modal").magnificPopup("open");
    }
});

function arrangeMeeting(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/arrange_meeting_action",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var redirectUrl = base_url + "profile/my_meetings";
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                if (data.success) {
                    reloadTimeOut(3000, redirectUrl);
                }
            }
        );
    } else {
        // alert("form is not valid");
    }
}

$(document).on("click", ".copy-meeting-coupon", function (e) {
    $(".copy-meeting-coupon")
        .prev()
        .notify("Code is copied successfully.", "success");
});

function addCharityCause(form) {
    $(form).validate();
    if ($(form).valid() == true) {
        $.post(
            base_url + "client/add_charity_cause",
            $(form).serialize(),
            function (data) {
                var data = JSON.parse(data);
                var alertMessage = getAlertMessages(data, form);
                $(form).find(".messageBox").html(alertMessage);
                if (data.success) {
                    reloadTimeOut(2000);
                }
            }
        );
    } else {
        // alert("form is not valid");
    }
}

/*Cookie Consent Popup Start*/

// Create cookie
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Delete cookie
function deleteCookie(cname) {
    const d = new Date();
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

// Read cookie
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Set cookie consent
function acceptCookieConsent() {
    deleteCookie("user_cookie_consent");
    setCookie("user_cookie_consent", 1, 30);
    document.getElementById("cookieNotice").style.display = "none";
}

function closeCookieConsent() {
    deleteCookie("user_cookie_consent");
    document.getElementById("cookieNotice").style.display = "none";
}

let cookie_consent = getCookie("user_cookie_consent");
if (cookie_consent != "") {
    document.getElementById("cookieNotice").style.display = "none";
} else {
    document.getElementById("cookieNotice").style.display = "block";
}

/*Cookie Consent Popup End*/
