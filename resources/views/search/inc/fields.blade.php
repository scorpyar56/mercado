<?php

use App\Models\PostValue;

if (!isset($languageCode) or empty($languageCode)) {
    $languageCode = config('app.locale', session('language_code'));
}

if (!function_exists("createFieldNodeTree")) {
    function createFieldNodeTree($options, $parent = null)
    {
        $result = [];
        foreach ($options as $option) {
            if ($option->parent_id == 0) {
                $option->parent_id = null;
            }
            if ($option->parent_id == $parent) {
                $subs = createFieldNodeTree($options, $option->id);
                $count = [];
                $posts = PostValue::where('option_id', $option->id)->groupBy('post_id')->get();
                foreach ($posts as $p) {
                    $count[] = $p->post_id;
                }

                $node = [
                    'id' => $option->id,
                    'selectable' => true,
                    'text' => $option->value,
                    'state' => [
                        'checked' => false,
                        'disabled' => false,
                        'selected' => false,
                        'expanded' => false
                    ]];
                if (count($subs) > 0) {
                    foreach ($subs as $s) {
                        $count = array_merge($count, $s['count']);
                    }
                    $node['nodes'] = $subs;
                }
                $node['count'] = array_unique($count);

                // Скрыть пустые элементы дерева
                //if (count($node['count']) > 0) {
                $result[] = $node;
                //}
            }
        }
        return $result;
    }
}

?>
@if (isset($customFields) and $customFields->count() > 0)
    <form id="cfForm" role="form" class="form" action="{{ $fullUrlNoParams }}" method="GET">
        {!! csrf_field() !!}
        <?php
        $clearAll = '';
        $firstFieldFound = false;
        ?>
        @foreach($customFields as $field)
            @continue(in_array($field->type, ['file', 'text', 'textarea']))
            <?php
            // Fields parameters
            $fieldId = 'cf.' . $field->tid;
            $fieldName = 'cf[' . $field->tid . ']';
            $fieldOld = 'cf.' . $field->tid;

            // Get the default value
            $defaulValue = (request()->filled($fieldOld)) ? request()->input($fieldOld) : $field->default;

            // Field Query String
            $fieldQueryString = '<input type="hidden" id="cf' . $field->tid . 'QueryString" value="' . httpBuildQuery(request()->except(['page', $fieldId])) . '">';

            // Clear All link
//            if (request()->filled('cf')) {
//                if (!$firstFieldFound) {
//                    $clearTitle = t('Clear all the :category\'s filters', ['category' => $cat->name]);
//                    $clearAll = '<a href="' . qsurl($fullUrlNoParams, request()->except(['page', 'cf']), null, false) . '" title="' . $clearTitle . '">
//									<span class="small" style="float: right;">' . t('Clear all') . '</span>
//								</a>';
//                    $firstFieldFound = true;
//                } else {
                    $clearAll = '';
//                }
//            }
            ?>

            @if ($field->type == 'number')

                <?php

                // Default min and max values
                $placeholderValue = DB::select('SELECT MIN(value) as min, MAX(value) as max FROM post_values WHERE field_id = ' . $field->tid);

                $defaulValue = (request()->filled($fieldOld))
                    ? request()->input($fieldOld)
                    : (
                    (is_array($field->default) && isset($field->default->value))
                        ? $field->default->value
                        : $field->default
                    );

                $minv = floor(request()->get($fieldId . '[0]') ?? $placeholderValue[0]->min);
                $maxv = ceil(request()->get($fieldId . '[1]') ?? $placeholderValue[0]->max);

                if($minv > $maxv){
                    $temp = $maxv;
                    $maxv = $minv;
                    $minv = $temp;
                }
                ?>
                <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                    <h5> <strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span> {!! $clearAll !!}</h5>
                </div>
                <div class="block-content list-filter" id="cf-block-{{ $field->tid }}">
                    <div class="form-inline">
                        <div class="form-group col-sm-4 no-padding">
                            <input id="{{ $fieldId }}[0]"
                                   name="{{ $fieldName }}[0]"
                                   type="number"
                                   class="form-control"
                                   value="{{ $minv }}"
                                   min="{{ $minv}}"
                                   max="{{ $maxv }}"
                            >
                        </div>
                        <div class="form-group col-sm-1 no-padding text-center hidden-xs"> -</div>
                        <div class="form-group col-sm-4 no-padding">
                            <input id="{{ $fieldId }}[1]"
                                   name="{{ $fieldName }}[1]"
                                   type="number"
                                   class="form-control"
                                   value="{{ $maxv }}"
                                   min="{{ $minv }}"
                                   max="{{ $maxv}}"
                            >
                        </div>
                        <div class="form-group col-sm-3 auto-width no-padding">
                            <button class="btn btn-default pull-right btn-block-xs go-button"
                                    type="submit"><span>{{ t('GO') }}</span></button>
                        </div>
                    </div>
                </div>
                @push('tree_script')
                    <script>
                        $(document).ready(function () {
                            toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                        });
                    </script>
                @endpush
                {!! $fieldQueryString !!}
                <div style="clear:both"></div>

            @endif

            @if ($field->type == 'checkbox')

            <!-- checkbox -->


                <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                    <h5><strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span>  {!! $clearAll !!}</h5>
                </div>
                <div class="block-content list-filter" id="cf-block-{{ $field->tid }}">
                    <div class="filter-content">
                        <div class="form-check">
                            <input id="{{ $fieldId }}"
                                   name="{{ $fieldName }}"
                                   value="1"
                                   type="checkbox"
                                   class="form-check-input"
                                    {{ ($defaulValue=='1') ? 'checked="checked"' : '' }}
                            >
                            <label class="form-check-label" for="{{ $fieldId }}">
                                {{ $field->name }}
                            </label>
                        </div>
                    </div>
                </div>
                @push('tree_script')
                    <script>
                        $(document).ready(function () {
                            toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                        });
                    </script>
                @endpush
                {!! $fieldQueryString !!}
                <div style="clear:both"></div>

            @endif
            @if ($field->type == 'checkbox_multiple' || $field->type == 'checkbox_multiple_or' || $field->type == 'checkbox_like_checkbox')
                        
                @if ($field->options->count() > 0)
                <!-- checkbox_multiple -->
                    <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                        <h5> <strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span> {!! $clearAll !!}</h5>
                    </div>
                    <div class="block-content list-filter" id="cf-block-{{ $field->tid }}">
                        <?php $cmFieldStyle = ($field->options->count() > 12) ? ' style="height: 250px; overflow-y: scroll;"' : ''; ?>
                        <div class="filter-content"{!! $cmFieldStyle !!}>
                            @foreach ($field->options as $option)
                                <?php
                                // Get the default value
                                $defaulValue = (request()->filled($fieldOld . '.' . $option->tid))
                                    ? request()->input($fieldOld . '.' . $option->tid)
                                    : (
                                    (is_array($field->default) && isset($field->default[$option->tid]) && isset($field->default[$option->tid]->value))
                                        ? $field->default[$option->tid]->value
                                        : $field->default
                                    );

                                // Field Query String
                                $fieldQueryString = '<input type="hidden" id="cf' . $field->tid . $option->tid . 'QueryString"
									value="' . httpBuildQuery(request()->except(['page', $fieldId . '.' . $option->tid])) . '">';
                                ?>
                                <div class="form-check">
                                    <!-- <input id="{{ $fieldId . '.' . $option->tid }}"
                                           name="{{ $fieldName . '[' . $option->tid . ']' }}"
                                           value="{{ $option->tid }}"
                                           type="checkbox"
                                           class="form-check-input"
                                            {{ ($defaulValue==$option->tid) ? 'checked="checked"' : '' }}
                                    >
                                    <label class="form-check-label" for="{{ $fieldId . '.' . $option->tid }}">
                                        {{ $option->value }}
                                    </label> -->
                                    <div class="cntr">
                                        <label for="{{ $fieldId . '.' . $option->tid }}" class="label-cbx">
                                            <input id="{{ $fieldId . '.' . $option->tid }}"
                                                name="{{ $fieldName . '[' . $option->tid . ']' }}"
                                                value="{{ $option->tid }}"
                                                type="checkbox"
                                                class="invisible"
                                                    {{ ($defaulValue==$option->tid) ? 'checked="checked"' : '' }}>
                                            <div class="checkbox">
                                                <svg width="14px" height="14px" viewBox="0 0 14 14">
                                                <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                                                <polyline points="4 8 6 10 11 5"></polyline>
                                                </svg>
                                            </div>
                                            {{ $option->value }}
                                        </label>
                                    </div>
                                </div>
                                {!! $fieldQueryString !!}
                            @endforeach
                        </div>
                    </div>
                    @push('tree_script')
                        <script>
                            $(document).ready(function () {
                                toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                            });
                        </script>
                    @endpush
                    <div style="clear:both"></div>
                @endif

            @endif

            @if ($field->type == 'radio')

                @if ($field->options->count() > 0)
                <!-- radio -->
                    <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                        <h5><strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span>  {!! $clearAll !!}</h5>
                    </div>
                    <div class="block-content list-filter" id="cf-block-{{ $field->tid }}">
                        <?php $rFieldStyle = ($field->options->count() > 12) ? ' style="height: 250px; overflow-y: scroll;"' : ''; ?>
                        <div class="filter-content"{!! $rFieldStyle !!}>
                            @foreach ($field->options as $option)
                                <div class="form-check">
                                    <!-- <input id="{{ $fieldId }}.{{ $option->tid }}"
                                           name="{{ $fieldName }}"
                                           value="{{ $option->tid }}"
                                           type="radio"
                                           class="form-check-input"
                                            {{ ($defaulValue==$option->tid) ? 'checked="checked"' : '' }}
                                    >
                                    <label class="form-check-label" for="{{ $fieldId }}.{{ $option->tid }}">
                                        {{ $option->value }}
                                    </label> -->
                                    <label for="{{ $fieldId }}.{{ $option->tid }}" class="radio">
                                        <input type="radio"
                                            name="{{ $fieldName }}"
                                            id="{{ $fieldId }}.{{ $option->tid }}"
                                            value="{{ $option->tid }}"
                                            class="hidden"
                                                {{ ($defaulValue==$option->tid) ? 'checked="checked"' : '' }}>
                                        <span class="label"></span>{{ $option->value }}
									</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @push('tree_script')
                        <script>
                            $(document).ready(function () {
                                toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                            });
                        </script>
                    @endpush
                    {!! $fieldQueryString !!}
                    <div style="clear:both"></div>
                @endif

            @endif
            @if ($field->type == 'select')

            <!-- select -->
                <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                    <h5><strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span>  {!! $clearAll !!}</h5>
                </div>
                <div class="block-content list-filter" id="cf-block-{{ $field->tid }}">
                    <div class="filter-content">
                        <?php
                        $select2Type = ($field->options->count() <= 10) ? 'selecter' : 'sselecter';
                        ?>
                        <select id="{{ $fieldId }}" name="{{ $fieldName }}" class="form-control {{ $select2Type }}">
                            <option value=""
                                    @if (old($fieldOld)=='' or old($fieldOld)==0)
                                    selected="selected"
                                    @endif
                            >
                                {{ t('Select') }}
                            </option>
                            @if ($field->options->count() > 0)
                                @foreach ($field->options as $option)
                                    <option value="{{ $option->tid }}"
                                            @if ($defaulValue==$option->tid)
                                            selected="selected"
                                            @endif
                                    >
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @push('tree_script')
                    <script>
                        $(document).ready(function () {
                            toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                        });
                    </script>
                @endpush
                {!! $fieldQueryString !!}
                <div style="clear:both"></div>

            @endif
            @if ($field->type == 'tree')


                <div class="block-title sidebar-header" id="cf-header-{{ $field->tid }}">
                    <h5> <strong>{{ $field->name }}</strong><span id="cf-chevron-{{ $field->tid }}" class="fa fa-chevron-right"></span> {!! $clearAll !!}</h5>
                </div>
                <div class="block-content list-filter list-tree" id="cf-block-{{ $field->tid }}">
                    <div class="filter-content">
                        <div id="{{ $fieldId }}_inputs"></div>
                        <div id="{{ $fieldId }}_tree"></div>


                        @push('tree_script')
                            <script>
                                $(document).ready(function () {

                                        <?php
                                        $cacheId = 'fieldOptions.' . $field->id . '.____' . $languageCode;
                                        $treedata = Cache::remember($cacheId, new \DateInterval('PT30M'), function () use ($field) {
                                            $treedata = json_encode(createFieldNodeTree($field->options));
                                            return $treedata;
                                        });

                                        $defaultValue = [];
                                        foreach ($field->options as $option) {
                                            if (request()->filled($fieldOld . '.' . $option->tid)) {
                                                $defaultValue[] = request()->input($fieldOld . '.' . $option->tid);
                                            }
                                        }
                                        ?>


                                    var treeData = <?php echo $treedata; ?>;
                                    var defaultV = <?php echo(json_encode($defaultValue ?? [])); ?>;

                                    function findNodeAndSetSelected(obj, id) {
                                        for (var i = 0; i < obj.length; i++) {
                                            if (obj[i].id === parseInt(id, 10)) {
                                                obj[i].state.selected = true;
                                                obj[i].state.expanded = true;
                                                break;
                                            } else {
                                                if (obj[i].hasOwnProperty('nodes')) {
                                                    findNodeAndSetSelected(obj[i].nodes, id);
                                                }
                                            }
                                        }
                                    }

                                    function setStats(obj) {
                                        let nodes = obj;
                                        for (var i = 0; i < nodes.length; i++) {
                                            nodes[i].tags = ["" + [nodes[i].count.length]];
                                            if (nodes[i].hasOwnProperty('nodes')) {
                                                nodes[i].nodes = setStats(nodes[i].nodes);
                                            }
                                        }
                                        return nodes;
                                    }

                                    defaultV.forEach(function (v) {
                                        findNodeAndSetSelected(treeData, v);
                                    });
                                    treeData = setStats(treeData);

                                    var tree = $('#{{ str_replace('.','\\\\.',$fieldId) }}_tree');
                                    tree.treeview({
                                        'data': treeData,
                                        'multiSelect': true,
                                        'levels': 1,
                                        'collapseIcon': "fa fa-chevron-down",
                                        'expandIcon': "fa fa-chevron-right",
                                        'selectedIcon': "unir-check1",
                                        'state.selected': true,
                                        'nodeIcon': "unir-uncheck",
                                        'showTags': true,
                                        "selectedColor": '#000000',
                                        'selectedBackColor': "#ffffff",
                                        'showCheckbox': true,
                                    });

                                    tree.treeview('getSelected').forEach(function (v) {
                                        tree.treeview('revealNode', [v.nodeId, {silent: true}]);
                                    });

                                    tree.on('nodeSelected nodeUnselected', function (event, node) {
                                        var selected = tree.treeview('getSelected');
                                        var selectedIDs = selected.map(function (value) {
                                            return value.id;
                                        });

                                        var selectedChildren = [];

                                        if (event.type==='nodeSelected') {
                                            if (node.hasOwnProperty('nodes')) {
                                                selectedChildren = node.nodes.map(function (value) {
                                                    return value.id;
                                                });
                                                selectedIDs = selectedIDs.concat(selectedChildren);
                                            }
                                        }

                                        if (event.type==='nodeUnselected') {
                                            if (node.hasOwnProperty('nodes')) {
                                                selectedChildren = node.nodes.map(function (value) {
                                                    return value.id;
                                                });

                                                selectedIDs = selectedIDs.filter(x => !selectedChildren.includes(x));
                                            }
                                        }

                                        var inp = $('#{{ str_replace('.','\\\\.',$fieldId) }}_inputs');
                                        var inps = selectedIDs.map(function (v) {
                                            return '<input id="{{ $fieldId }}.' + v + '" ' +
                                                'name="{{ $fieldName }}[' + v + ']" ' +
                                                'value=' + v + ' ' +
                                                'type="hidden"' +
                                                'data-tree="tree">';
                                        });
                                        inp.html(inps.join(''));

                                        var fieldQueryString = $('#{{ str_replace('.','',$fieldId) }}QueryString').val();
                                        if (fieldQueryString != '') {
                                            fieldQueryString = fieldQueryString + '&';
                                        }

                                        var qString = selectedIDs.map(function (v) {
                                            return '{{ str_replace('.','[',$fieldId) }}][' + v + ']=' + v;
                                        });
                                        fieldQueryString += qString.join("&");

                                        var searchUrl = baseUrl + '?' + fieldQueryString;
                                        redirect(searchUrl);
                                    });

                                    var selected = tree.treeview('getSelected');
                                    var selectedIDs = selected.map(function (value) {
                                        return value.id;
                                    });
                                    var inp = $('#{{ str_replace('.','\\\\.',$fieldId) }}_inputs');
                                    var inps = selectedIDs.map(function (v) {
                                        return '<input id="{{ $fieldId }}.' + v + '" ' +
                                            'name="{{ $fieldName }}[' + v + ']" ' +
                                            'value=' + v + ' ' +
                                            'type="hidden">';
                                    });
                                    inp.html(inps.join(''));
                                });
                            </script>
                        @endpush
                    </div>
                </div>
                @push('tree_script')
                    <script>
                        $(document).ready(function () {
                            toggleFilter({{ $field->tid }}, {{ request()->filled($fieldOld) || $field->opened==1 }});
                        });
                    </script>
                @endpush
                {!! $fieldQueryString !!}
                <div style="clear:both"></div>

            @endif

        @endforeach
    </form>
    <div style="clear:both"></div>
@endif

@section('after_scripts')
    @parent
    <script>
        $(document).ready(function () {
            /* Select */
            $('#cfForm').find('select').change(function () {
                /* Get full field's ID */
                var fullFieldId = $(this).attr('id');

                /* Get full field's ID without dots */
                var jsFullFieldId = fullFieldId.split('.').join('');

                /* Get real field's ID */
                var tmp = fullFieldId.split('.');
                if (typeof tmp[1] !== 'undefined') {
                    var fieldId = tmp[1];
                } else {
                    return false;
                }

                /* Get saved QueryString */
                var fieldQueryString = $('#' + jsFullFieldId + 'QueryString').val();

                /* Add the field's value to the QueryString */
                if (fieldQueryString != '') {
                    fieldQueryString = fieldQueryString + '&';
                }
                fieldQueryString = fieldQueryString + 'cf[' + fieldId + ']=' + $(this).val();

                /* Redirect to the new search URL */
                var searchUrl = baseUrl + '?' + fieldQueryString;
                redirect(searchUrl);
            });

            /* Checkbox */
            $('#cfForm').find('input[type=checkbox]').click(function () {
                /* Get full field's ID */
                var fullFieldId = $(this).attr('id');

                /* Get full field's ID without dots */
                var jsFullFieldId = fullFieldId.split('.').join('');

                /* Get real field's ID */
                var tmp = fullFieldId.split('.');
                if (typeof tmp[1] !== 'undefined') {
                    var fieldId = tmp[1];
                    if (typeof tmp[2] !== 'undefined') {
                        var fieldOptionId = tmp[2];
                    }
                } else {
                    return false;
                }

                /* Get saved QueryString */
                var fieldQueryString = $('#' + jsFullFieldId + 'QueryString').val();

                /* Check if field is checked */
                if ($(this).prop('checked') == true) {
                    /* Add the field's value to the QueryString */
                    if (fieldQueryString != '') {
                        fieldQueryString = fieldQueryString + '&';
                    }
                    if (typeof fieldOptionId !== 'undefined') {
                        fieldQueryString = fieldQueryString + 'cf[' + fieldId + '][' + fieldOptionId + ']=' + rawurlencode($(this).val());
                    } else {
                        fieldQueryString = fieldQueryString + 'cf[' + fieldId + ']=' + $(this).val();
                    }
                }

                /* Redirect to the new search URL */
                var searchUrl = baseUrl + '?' + fieldQueryString;
                redirect(searchUrl);
            });

            /* Radio */
            $('#cfForm').find('input[type=radio]').click(function () {
                var fieldName = $(this).attr('name');
                var fieldQStringSelector = fieldName.replace('[', '').replace(']', '') + 'QueryString';
                var fieldQueryString = $('#' + fieldQStringSelector).val();

                /* Check if field is checked */
                if ($(this).prop('checked') == true) {
                    /* Add the field's value to the QueryString */
                    if (fieldQueryString != '') {
                        fieldQueryString = fieldQueryString + '&';
                    }
                    fieldQueryString = fieldQueryString + fieldName + '=' + $(this).val();
                }

                /* Redirect to the new search URL */
                var searchUrl = baseUrl + '?' + fieldQueryString;
                redirect(searchUrl);
            });
        });

        function toggleFilter(id, opened = false) {
            if (!opened) {
                $("#cf-block-" + id).hide();
            } else {
                $("#cf-header-"+id).addClass("has-arrow");
                $("#cf-chevron-"+id).addClass("fa-chevron-down").removeClass("fa-chevron-right");
            }

            $("#cf-header-"+id).click(function () {
                $("#cf-block-"+id).toggle(0, function() {
                    if ($("#cf-block-"+id).is(":visible")) {
                        $("#cf-chevron-"+id).addClass("fa-chevron-down").removeClass("fa-chevron-right");
                        $("#cf-header-"+id).addClass("has-arrow");
                    } else {
                        $("#cf-chevron-"+id).removeClass("fa-chevron-down").addClass("fa-chevron-right");
                        $("#cf-header-"+id).removeClass("has-arrow");
                    }
                });
            });
        }

    </script>
    <script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
    @stack('tree_script')
@endsection

