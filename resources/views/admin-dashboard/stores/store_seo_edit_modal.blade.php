
                                <form action="{{route('admin.stores.update_seo',$storeSeoRule)}}" class="gy-3 form-validate is-alter seo_form" method="POST">
                                    @csrf
                                    @method('PUT')
                                    {{-- <input type="hidden" name="store_editor" value="1"> --}}
                                    <input type="hidden" name="seo_id" value="{{$storeSeoRule->id}}">
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="full-name-1">Meta Keyword</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="keyword" name="meta_keyword" value="{{ $storeSeoRule->meta_keyword }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label" for="vsale_commission">Meta Description</label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" id="meta_description" value="" name="meta_description" required>{{ $storeSeoRule->meta_description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">update</button>
                                            </div>
                                        </div>
                                    </div>
                                        
                                    </div>
                                </form>
     
