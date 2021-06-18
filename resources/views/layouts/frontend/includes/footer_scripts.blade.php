<!-- js -->
{{-- <script src="{{ asset('frontend/vendor/jquery/jquery.min.js')}}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('frontend/vendor/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{ asset('frontend/vendor/nouislider/nouislider.min.js')}}"></script>
<script src="{{ asset('frontend/vendor/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{ asset('frontend/vendor/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{ asset('frontend/vendor/select2/js/select2.min.js')}}"></script>
<script src="{{ asset('frontend/js/number.js')}}"></script>
<script src="{{ asset('frontend/js/main.js')}}"></script>
<script src="{{ asset('frontend/js/header.js')}}"></script>
<script src="{{ asset('frontend/vendor/svg4everybody/svg4everybody.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script>
    svg4everybody();
</script>
<script>
    $('div.alert').not('.alert-important').delay(2000).fadeOut(350);
    
</script>
<script>$(".search__input").keydown(function(e) {
    
    switch (e.which) {
      case 40:
      
        e.preventDefault(); // prevent moving the cursor
        $('li:not(:last-child).suggestions__item.selected').removeClass('selected')
          .next().addClass('selected');
          console.log($('li.suggestions__item.selected a').text());
          $('.search__input').val($('li.suggestions__item.selected a').text());


        break;
      case 38:
      
        e.preventDefault(); // prevent moving the cursor
        $('li:not(:first-child).suggestions__item.selected').removeClass('selected')
          .prev().addClass('selected');
          console.log($('li.suggestions__item.selected a').text());
          $('.search__input').val($('li.suggestions__item.selected a').text());
        break;
    }
  });
  $(function () {
  $('[data-toggle="popover"]').popover({
    trigger: 'hover'
  });
});
  </script>