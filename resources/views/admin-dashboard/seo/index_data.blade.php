@if(count($seo_rules))
<div class="nk-tb-item nk-tb-head">
    <div class="nk-tb-col"><span class="sub-text">URL</span></div>
    <div class="nk-tb-col"><span class="sub-text">Values</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text">Status</span></div>
    <div class="nk-tb-col nk-tb-col-tools text-right">
    <span class="sub-text">Action</span>
    </div>
</div><!-- .nk-tb-item -->
@foreach ($seo_rules as $seo)
    <div class="nk-tb-item">

        <div class="nk-tb-col">
            <div class="tb-lead">
                <span>
                    <a href="javascript:void(0)" class="a_link edit-form" data-id="{{ $seo->id }}">{{$seo->url}}</a>
                </span>
            </div>
        </div>
        <div class="nk-tb-col">
            @foreach ($seo->ruleData as $key => $rule)
                <span><b>{{ ucfirst(str_replace('_', ' ', $rule->key)) }}:</b> </span><span>{{ $rule->value }}</span><br>
            @endforeach
        </div>
        <div class="nk-tb-col nk-tb-col-tools text-right">
            <span>{!! $seo->is_enabled == 1 ? '<span class="tb-status badge badge-success">Enable</span>' : '<span class="tb-status badge badge-danger">Disable</span>' !!}</span>
        </div>
        <div class="nk-tb-col nk-tb-col-tools">
            <ul class="nk-tb-actions gx-1">
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li>
                                    <a href="javascript:void(0)" class="edit-form" data-id="{{ $seo->id }}">
                                        <em class="icon ni ni-edit"></em>
                                        <span>Edit</span>
                                    </a>
                                </li>
                                <li>
                                    <a  form_id="delete-blog-{{$seo->id}}"  class="delete" style="cursor: pointer">
                                        <em class="icon ni ni-trash-fill"></em>
                                        <span>Delete</span>
                                    </a>
                                    <form action="{{ route(getAdminPrefix() . '.seo.destroy', $seo) }}" id="delete-blog-{{$seo->id}}" method="POST" class="m-0">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div><!-- .nk-tb-item -->

@endforeach

@else
<h3 class="m-auto text-center py-5">No record found</h3>
@endif
