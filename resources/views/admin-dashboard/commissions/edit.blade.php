
<form action="{{route('admin.commissions.update', $commission)}}" class="gy-3 form-validate is-alter update_cashback_form" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="phone-no-1">Order Value</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->order_value}}" name="order_value">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="phone-no-1">Network Commission</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->network_commission}}" name="network_commission" >
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="phone-no-1">Cashback Amount</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" id="phone-no-1" value="{{$commission->amount}}" name="amount" >
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="default-06">Status</label>
              
                    <select class="form-control form-select select-2" name="status" required>
                        @foreach ($statuses as $status)
                        <option @if($status->id == $commission->status) selected @endif value="{{$status->id}}">{{$status->status}}</option>
                            
                        @endforeach
                        
                    </select>
                    
            </div>
        </div>
        
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>
                            