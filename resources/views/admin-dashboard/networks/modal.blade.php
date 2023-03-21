<h4 class="nk-modal-title">{{ $networkFullName }} Settings</h4>
<div class="nk-modal-text">
    <form class="network_form_settings" action="{{ route('admin.settings.settings_save') }}" method="post">
        @csrf
        <div class="form-group">
            <label class="form-label" for="{{ $networkName }}_website_id">Website ID</label>
            <div class="form-control-wrap">
                <input type="text" class="form-control" name="{{ $networkName }}_website_id" id="{{ $networkName }}_website_id"
                value="{{ isset($settings[$networkName . '_website_id']) ? $settings[$networkName . '_website_id'] : '' }}" required>
            </div>
        </div>
        @if (isset($settings[$networkName . '_requestor_id']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_requestor_id">Requestor ID/ CID</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_requestor_id" id="{{ $networkName }}_requestor_id"
                        value="{{ $settings[$networkName . '_requestor_id'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_publisher_id']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_publisher_id">Publisher ID</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_publisher_id" id="{{ $networkName }}_publisher_id"
                        value="{{ $settings[$networkName . '_publisher_id'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_authorization_token']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_authorization_token">Authorization Token</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_authorization_token" id="{{ $networkName }}_authorization_token"
                        value="{{ $settings[$networkName . '_authorization_token'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_campaignid']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_campaignid">Campaign ID</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_campaignid" id="{{ $networkName }}_campaignid"
                        value="{{ $settings[$networkName . '_campaignid'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_api_key']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_api_key">Api Key</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_api_key" id="{{ $networkName }}_api_key"
                        value="{{ $settings[$networkName . '_api_key'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_user_name']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_user_name">Username</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_user_name" id="{{ $networkName }}_user_name"
                        value="{{ $settings[$networkName . '_user_name'] }}" required>
                </div>
            </div>
        @endif
        @if (isset($settings[$networkName . '_password']))
            <div class="form-group">
                <label class="form-label" for="{{ $networkName }}_password">Password</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="{{ $networkName }}_password" id="{{ $networkName }}_password"
                        value="{{ $settings[$networkName . '_password'] }}" required>
                </div>
            </div>
        @endif
        <div class="nk-modal-action">
            <button type="submit" class="btn btn-mw btn-primary">Save</button>
        </div>

    </form>
</div>
<div class="nk-modal-action">
    <p class="setting-message"></p>
</div>
