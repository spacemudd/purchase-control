@if($data->terms_json)
    <div style="font-size:12px;padding:10px;margin:20px 0;">
        <h4 style="font-size:12px;margin:5px 0;">After Sale Service / Maintenance Terms</h4>
        <p style="margin-left: 29px;">
            @if(isset($data->terms_json->Others))
                {!! nl2br($data->terms_json->Others) !!}
            @endif
        </p>
        @foreach($data->terms_json as $type => $terms)
            @if($type === 'Others')
                @break
            @endif
            <h4 style="font-size:12px;margin:5px 0;">{{ $type }}</h4>
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
