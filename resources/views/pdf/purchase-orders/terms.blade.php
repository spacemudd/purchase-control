@if($data->terms_json)
    <div style="padding:10px;margin:20px 0;">
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
            <h4>{{ $type }}</h4>
            <ul>
                @foreach($terms as $term)
                    @if($term->value)
                        <li>{{ $term->value->value }}:
                            @if(isset($term->enabled) && $term->enabled)
                                No ☐ <b>Yes ☒</b>
                            @else
                                No ☒ <b>Yes ☐</b>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        @endforeach
    </div>
@endif
