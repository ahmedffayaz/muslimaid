<div class="nk-modal-text">
    <form action="" method="post" id="settings_form">
        @csrf
        <input type="hidden" name="network_id" value="{{ $network->id }}">
        <input type="hidden" name="network_name" value="{{ $network->name }}">
        @if (getImporterYMLSettings('Networks_' . $network->name . '_Importer_Stores'))
            <div class="custom-control custom-control-sm custom-checkbox mr-2">
                <input @if (@$network->importerSetting->import_stores) checked @endif type="checkbox" class="custom-control-input importer-setting" id="stores" name="stores">
                <label class="custom-control-label" for="stores">Stores</label>
            </div>
        @endif
        @if (getImporterYMLSettings('Networks_' . $network->name . '_Importer_Vouchers'))
            <div class="custom-control custom-control-sm custom-checkbox mr-2">
                <input @if (@$network->importerSetting->import_vouchers) checked @endif type="checkbox" class="custom-control-input importer-setting" id="vouchers" name="vouchers">
                <label class="custom-control-label" for="vouchers">Vouchers</label>
            </div>
        @endif
        @if (getImporterYMLSettings('Networks_' . $network->name . '_Importer_Cashbacks'))
            <div class="custom-control custom-control-sm custom-checkbox mr-2">
                <input @if (@$network->importerSetting->import_cashbacks) checked @endif type="checkbox" class="custom-control-input importer-setting" id="cashback" name="cashback">
                <label class="custom-control-label" for="cashback">Cashbacks</label>
            </div>
        @endif
        @if (getImporterYMLSettings('Networks_' . $network->name . '_Imprter_Categories'))
            <div class="custom-control custom-control-sm custom-checkbox mr-2">
                <input @if (@$network->importerSetting->import_categories) checked @endif type="checkbox" class="custom-control-input importer-setting" id="category" name="category">
                <label class="custom-control-label" for="category">Categories</label>
            </div>
        @endif
    </form>
</div>
<div class="nk-modal-action">
    <a href="{{ route(getAdminPrefix() . '.importer.import') }}" class="btn btn-sm btn-mw btn-primary run-importer @if (getImporterYMLSettings('Networks_' . $network->name . '_Importer_Stores') === 0 &&
            getImporterYMLSettings('Networks_' . $network->name . '_Importer_Vouchers') === 0 &&
            getImporterYMLSettings('Networks_' . $network->name . '_Importer_Cashbacks') === 0) disabled @endif">Run Importer</a>
    <a href="" class="btn btn-sm btn-mw btn-primary save_importer @if (getImporterYMLSettings('Networks_' . $network->name . '_Importer_Stores') === 0 &&
            getImporterYMLSettings('Networks_' . $network->name . '_Importer_Vouchers') === 0 &&
            getImporterYMLSettings('Networks_' . $network->name . '_Importer_Cashbacks') === 0) disabled @endif">Save for later</a>
</div>
