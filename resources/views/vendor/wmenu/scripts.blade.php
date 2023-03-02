<script>
	var menus = {
		"oneThemeLocationNoMenus" : "",
		"moveUp": "Move up",
		"moveDown": "Mover down",
		"moveToTop": "Move top",
		"moveUnder": "Move under of %s",
		"moveOutFrom": "Out from under  %s",
		"under": "Under %s",
		"outFrom": "Out from %s",
		"menuFocus": "%1$s. Element menu %2$d of %3$d.",
		"subMenuFocus": "%1$s. Menu of subelement %2$d of %3$s."
	};
	var arraydata = [];
	var addcustommenur = '{{ route("admin.add.custom.menu") }}';
	var updateitemr = '{{ route("admin.update.item")}}';
	var generatemenucontrolr = '{{ route("admin.generate.menu.control") }}';
	var deleteitemmenur = '{{ route("admin.delete.item.menu") }}';
	var deletemenugr = '{{ route("admin.delete.menu") }}';
	var createnewmenur = '{{ route("admin.create.new.menu") }}';
	var csrftoken = "{{ csrf_token() }}";
	var menuwr = "{{ url()->current() }}";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': csrftoken
		}
	});
</script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/scripts.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/scripts2.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/menu.js')}}"></script>
