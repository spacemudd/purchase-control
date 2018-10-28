@if($data->terms_json)
    <div style="font-size:12px;padding:10px;margin:20px 0;">
        @if(isset($data->terms_json->Others))
            <h4>Other Terms</h4>
            <p style="margin-left: 29px;">
                {!! nl2br($data->terms_json->Others) !!}
            </p>
        @endif
        @foreach($data->terms_json as $type => $terms)
            @if($type === 'Others')
                @break
            @endif
            <h4 style="font-size:13px;">{{ $type }}</h4>
            @foreach($terms as $term)
                <span>
                    {{ $term->value->value }}
                    @if(isset($term->enabled) && $term->enabled)
                        No ☐ <b>Yes ☒</b>
                    @else
                        No ☒ <b>Yes ☐</b>
                    @endif
                </span>
            @endforeach
        @endforeach
    </div>
@endif
