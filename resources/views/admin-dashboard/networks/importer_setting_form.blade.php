<form action="" method="post" id="settings_form">
    @csrf
    <input type="hidden" name="network_id" value="{{$network->id}}">
    <input type="hidden" name="network_name" value="{{$network->name}}">
    <div class="custom-control custom-control-sm custom-checkbox mr-2">
        <input @if(@$network->importerSetting->import_stores) checked @endif type="checkbox"
        class="custom-control-input" id="stores" name="stores">
        <label class="custom-control-label" for="stores">Stores</label>
    </div>
    @if (!(strpos(strtolower($network), 'awin') !== false))
    <div class="custom-control custom-control-sm custom-checkbox mr-2">
        <input @if(@$network->importerSetting->import_vouchers) checked @endif type="checkbox"
        class="custom-control-input" id="vouchers" name="vouchers">
        <label class="custom-control-label" for="vouchers">Vouchers</label>
    </div>
    @endif
    <div class="custom-control custom-control-sm custom-checkbox mr-2">
        <input @if(@$network->importerSetting->import_cashbacks) checked @endif type="checkbox"
        class="custom-control-input" id="cashback" name="cashback">
        <label class="custom-control-label" for="cashback">Cashbacks</label>
    </div>
</form>