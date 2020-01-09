@if (isset($catChildren) and !empty($catChildren) and isset($baseCatURL) and !empty($baseCatURL))
{{--        <div class="container hide-xs">--}}
        <div class="container">
            <div class="category-links">
                <ul>
                    @foreach ($catChildren as $iSubCat)
                        <li>
                            <a href="{{ $baseCatURL }}/{{ $iSubCat->slug }}">
                                {{ $iSubCat->name }}
                            </a>
                        </li>
                        <li class="separator-mob">|</li>
                    @endforeach
                </ul>
            </div>
        </div>
@endif
