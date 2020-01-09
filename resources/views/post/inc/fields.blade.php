<?php
use Illuminate\Support\Facades\Cache;

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
                    $node['nodes'] = $subs;
                }
                $result[] = $node;
            }
        }
        return $result;
    }
}
?>

@if (isset($fields) and $fields->count() > 0)
    @foreach($fields as $field)
        <?php
        // Fields parameters
        $fieldId = 'cf.' . $field->tid;
        $fieldName = 'cf[' . $field->tid . ']';
        $fieldOld = 'cf.' . $field->tid;

        // Errors & Required CSS
        $requiredClass = ($field->required == 1) ? 'required' : '';
        $errorClass = (isset($errors) && $errors->has($fieldOld)) ? ' is-invalid' : '';

        // Get the default value
        $defaultValue = (isset($oldInput) && isset($oldInput[$field->tid])) ? $oldInput[$field->tid] : $field->default;

        ?>

        @if ($field->type == 'checkbox')
            <!-- checkbox -->
            <div class="form-group row {{ $requiredClass }}"
                 style="margin-top: -10px;">
                <label class="col-md-2 col-form-label"
                       for="{{ $fieldId }}"></label>
                <div class="col-md-9">
                    <div class="form-check pt-2">
                        <input id="{{ $fieldId }}"
                               name="{{ $fieldName }}"
                               value="1"
                               type="checkbox"
                               class="form-check-input{{ $errorClass }}"
                                {{ ($defaultValue=='1') ? 'checked="checked"' : '' }}
                        >

                        <label class="form-check-label" for="{{ $fieldId }}">
                            {{ $field->name }}
                        </label>
                    </div>
                    <small id=""
                           class="form-text text-muted">{!! $field->help !!}</small>
                </div>
            </div>

        @elseif ($field->type == 'checkbox_multiple' || $field->type == 'checkbox_multiple_or')

            @if ($field->options->count() > 0)
                <!-- checkbox_multiple -->
                <?php
                $select2Type = 'sselecter';
                ?>
                <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                    <label class="ns-form-label {{ $errorClass }}"
                           for="{{ $fieldId }}">
                        {{ $field->name }}
                        @if ($field->required == 1)
                            <sup>*</sup>
                        @endif
                    </label>
                    <select id="{{ $fieldId }}" name="{{ $fieldName }}"
                            data-placeholder="{{ t('Select', [], 'global', $languageCode) }}"
                            class="form-control {{ $select2Type . $errorClass }}">
                        <option/>
                        @if ($field->options->count() > 0)
                            @foreach ($field->options as $option)
                                <?php
                                // Get the default value
                                    if  (isset($oldInput) && isset($oldInput[$field->tid]) && isset($oldInput[$field->tid][$option->tid])) {
                                        $defaultValue = $oldInput[$field->tid][$option->tid];
                                    } elseif (isset($oldInput) && isset
                                        ($oldInput[$field->tid])) {
                                        $defaultValue = $oldInput[$field->tid];
                                    } elseif (is_array($field->default) && isset($field->default[$option->tid]) && isset($field->default[$option->tid]->tid)) {
                                        $defaultValue =$field->default[$option->tid]->tid;
                                    } else {
                                        $defaultValue = $field->default;
                                    }
                                ?>
                                <option value="{{ $option->tid }}"
                                        @if ($defaultValue==$option->tid)
                                        selected="selected"
                                        @endif
                                >
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>


            @endif

        @elseif ($field->type == 'checkbox_like_checkbox')

            @if ($field->options->count() > 0)
                <!-- checkbox_like_checkbox -->

                <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                    <label class="ns-form-label {{ $errorClass }}"
                           for="{{ $fieldId }}">
                        {{ $field->name }}
                        @if ($field->required == 1)
                            <sup>*</sup>
                        @endif
                    </label>
                    <div class="row" style="margin:0">
                        @foreach ($field->options as $option)
                            <?php
                            // Get the default value
                            $defaultValue = (isset($oldInput) && isset($oldInput[$field->tid]) && isset($oldInput[$field->tid][$option->tid]))
                                ? $oldInput[$field->tid][$option->tid]
                                : (
                                (is_array($field->default) && isset($field->default[$option->tid]) && isset($field->default[$option->tid]->tid))
                                    ? $field->default[$option->tid]->tid
                                    : $field->default
                                );
                            ?>
                            <div class="form-check pt-2 col-md-4 col-sm-6">
                                <label for="{{ $fieldId . '.' . $option->tid }}"
                                       class="label-cbx">
                                    <input id="{{ $fieldId . '.' . $option->tid }}"
                                           name="{{ $fieldName . '[' . $option->tid . ']' }}"
                                           value="{{ $option->tid }}"
                                           type="checkbox"
                                           class="invisible"
                                            {{ ($defaultValue==$option->tid) ? 'checked="checked"' : '' }}
                                    >
                                    <div class="checkbox">
                                        <svg width="14px" height="14px"
                                             viewBox="0 0 14 14">
                                            <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                                            <polyline
                                                    points="4 8 6 10 11 5"></polyline>
                                        </svg>
                                    </div>
                                    {{ $option->value }}
                                </label>

                            </div>
                        @endforeach
                        <small id=""
                               class="form-text text-muted">{!! $field->help !!}</small>
                    </div>
                </div>

            @endif

        @elseif ($field->type == 'file')

            <!-- file -->
            <div class="ns-form-group {{ $requiredClass }}">
                <div>
                    <!-- <div class="mb10">
                        <input id="{{ $fieldId }}" name="{{ $fieldName }}"
                               type="file" class="file{{ $errorClass }}">
                    </div> -->
                    <div class="button-wrap">
                        <label class ="custom-button-upfile" for="{{ $fieldId }}"> Upload CV </label>
                        <input class="custom-upfile" id="{{ $fieldId }}" name="{{ $fieldName }}" type="file" class="file{{ $errorClass }}">
                        <div id="fileName"></div>
                    </div>
                    <script>
                        $(".custom-upfile").on('change', function () {
                            let e = $(".custom-upfile")[0].value.split('\\');
                            $('#fileName').html(e[e.length - 1]);
                        });
                    </script>
                    <small style="display:block;"  class="text-muted">
                        {!! $field->help !!} {{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')], 'global', $languageCode) }}
                    </small>
                    @if (!empty($field->default) and $disk->exists($field->default))
                        <div>
                            <a class="btn btn-default"
                               href="{{ fileUrl($field->default) }}"
                               target="_blank">
                                <i class="icon-attach-2"></i> {{ t('Download') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        @elseif ($field->type == 'radio')
            <?php
            if (is_array($defaultValue)) {
                foreach ($defaultValue as $k => $v) {
                    $d = $k;
                }
            } else {
                $d = $defaultValue;
            }
            ?>
            @if ($field->options->count() > 0)
                <!-- radio -->

                <?php
                $select2Type = 'sselecter';
                ?>
                <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                    <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                           for="{{
                    $fieldId }}">
                        {{ $field->name }}
                        @if ($field->required == 1)
                            <sup>*</sup>
                        @endif
                    </label>
                    <select id="{{ $fieldId }}" name="{{ $fieldName }}"
                            data-placeholder="{{ t('Select', [], 'global', $languageCode) }}"
                            class="form-control {{ $select2Type . $errorClass }}"
                    >
                        <option/>
                        @if ($field->options->count() > 0)
                            @foreach ($field->options as $option)
                                <?php
                                // Get the default value
                                if  (isset($oldInput) && isset($oldInput[$field->tid]) && isset($oldInput[$field->tid][$option->tid])) {
                                    $defaultValue = $oldInput[$field->tid][$option->tid];
                                } elseif (isset($oldInput) && isset
                                    ($oldInput[$field->tid])) {
                                    $defaultValue = $oldInput[$field->tid];
                                } elseif (is_array($field->default) && isset($field->default[$option->tid]) && isset($field->default[$option->tid]->tid)) {
                                    $defaultValue =$field->default[$option->tid]->tid;
                                } else {
                                    $defaultValue = $field->default;
                                }
                                ?>
                                <option value="{{ $option->tid }}"
                                        @if ($defaultValue==$option->tid)
                                        selected="selected"
                                        @endif
                                >
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif

        @elseif ($field->type == 'select')

            <!-- select -->
            <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                       for="{{
                    $fieldId }}">
                    {{ $field->name }}
                    @if ($field->required == 1)
                        <sup>*</sup>
                    @endif
                </label>
                <div>
                    <?php
                    $select2Type = ($field->options->count() <= 10) ? 'selecter' : 'sselecter';
                    ?>
                    <select id="{{ $fieldId }}" name="{{ $fieldName }}"
                            data-placeholder="{{ t('Select', [], 'global', $languageCode) }}"
                            class="form-control {{ $select2Type . $errorClass }}">
                        <option/>
                        @if ($field->options->count() > 0)
                            @foreach ($field->options as $option)
                                <option value="{{ $option->tid }}"
                                        @if ($defaultValue==$option->tid)
                                        selected="selected"
                                        @endif
                                >
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <small id=""
                       class="form-text text-muted">{!! $field->help !!}</small>
            </div>

        @elseif ($field->type == 'textarea')

            <!-- textarea -->
            <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                       for="{{
                    $fieldId }}">
                    {{ $field->name }}
                    @if ($field->required == 1)
                        <sup>*</sup>
                    @endif
                </label>
                <div>
					<textarea class="form-control{{ $errorClass }}"
                              id="{{ $fieldId }}"
                              name="{{ $fieldName }}"
                              placeholder="{{ $field->name }}"
                              rows="10">{{ $defaultValue }}</textarea>
                    <small id=""
                           class="form-text text-muted">{!! $field->help !!}</small>
                </div>
            </div>

        @elseif ($field->type == 'tree')

            <!-- tree -->
            <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                       for="{{
                    $fieldId }}">
                    {{ $field->name }}
                    @if ($field->required == 1)
                        <sup>*</sup>
                    @endif
                </label>
                <div>
                    <div id="{{ $fieldId }}_inputs"></div>
                    <div id="{{ $fieldId }}_selectors" class="row ns-row">
                        <div class="col-sm-6">
                            <select id="{{ $fieldId }}_select1"
                                    class="form-control {{ $errorClass }}"></select>
                        </div>
                        <div class="col-sm-6">
                            <select id="{{ $fieldId }}_select2"
                                    class="form-control {{ $errorClass }}"></select>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function () {
                                <?php
                                $cacheId = 'fieldOptions.' . $field->id . '.' . $languageCode;
                                $treedata = Cache::remember($cacheId, new \DateInterval('PT30M'), function () use ($field) {
                                    $treedata = json_encode(createFieldNodeTree($field->options));
                                    return $treedata;
                                });
                                ?>

                            var treeData = <?php echo $treedata; ?>;
                            var defaultV = <?php echo(json_encode(array_keys($defaultValue ?? []))); ?>;

                            function findNodeAndSetSelected(obj, id) {
                                for (var i = 0; i < obj.length; i++) {
                                    if (obj[i].id === id) {
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

                            function updateInputs() {
                                let s1 = $('#{{ str_replace('.','\\\\.',
                            $fieldId) }}_select1').val();
                                let s2 = $('#{{ str_replace('.','\\\\.',
                            $fieldId) }}_select2').val();
                                var selectedIDs = [s1, s2];
                                var inp = $('#{{ str_replace('.','\\\\.',$fieldId) }}_inputs');
                                var inps = selectedIDs.map(function (v) {
                                    return '<input id="{{ $fieldId }}.' + v + '" ' +
                                        'name="{{ $fieldName }}[' + v + ']" ' +
                                        'value=' + v + ' ' +
                                        'type="hidden">';
                                });
                                inp.html(inps.join(''));
                            }


                            defaultV.forEach(function (v) {
                                findNodeAndSetSelected(treeData, v);
                            });

                            var select1empty = true;
                            var select2data = [];
                            var newSelect1Data = treeData.map(function (v) {
                                if (v.state.selected) {
                                    select1empty = false;
                                    select2data = v.nodes;
                                }
                                return {
                                    id: v.id,
                                    text: v.text,
                                    selected: v.state.selected,
                                    nodes: v.nodes
                                }
                            });
                            var newSelect2Data = [];

                            var select_1 = $('#{{ str_replace('.','\\\\.',
                            $fieldId) }}_select1');
                            select_1.select2({
                                data: newSelect1Data,
                                placeholder: 'Select',
                                width: '100%',
                                multiple: false,
                                language: select2Language,
                                dropdownAutoWidth: 'true',
                                minimumResultsForSearch: -1
                            });

                            if (select1empty) {
                                select_1.val(null).trigger('change');
                            } else {
                                var select2empty = true;
                                newSelect2Data = select2data.map(function (v) {
                                    if (v.state.selected) {
                                        select2empty =
                                            false;
                                    }
                                    return {
                                        id: v.id,
                                        text: v.text,
                                        selected: v.state.selected
                                    }
                                });
                            }

                            var select_2 = $('#{{ str_replace('.','\\\\.',
                            $fieldId) }}_select2');
                            select_2.select2({
                                data: newSelect2Data,
                                placeholder: 'Select',
                                width: '100%',
                                multiple: false,
                                language: select2Language,
                                dropdownAutoWidth: 'true',
                                minimumResultsForSearch: -1
                            });
                            select_2.on('select2:select', function (e) {
                                updateInputs();
                            });

                            select_1.on('select2:select', function (e) {
                                select2data = e.params.data.nodes;
                                select_2.html('');
                                select_2.off('select2:select');
                                select_2.off('select2:unselect');
                                select_2.val(null).trigger('change');

                                var select2empty = true;
                                newSelect2Data = select2data.map(function (v) {
                                    if (v.state.selected) {
                                        select2empty =
                                            false;
                                    }
                                    return {
                                        id: v.id,
                                        text: v.text,
                                        selected: v.state.selected
                                    }
                                });

                                select_2.select2({
                                    data: newSelect2Data,
                                    placeholder: 'Select',
                                    width: '100%',
                                    multiple: false,
                                    language: select2Language,
                                    dropdownAutoWidth: 'true',
                                    minimumResultsForSearch: -1
                                });
                                if (select2empty) {
                                    select_2.val(null).trigger('change');
                                }
                                select_2.on('select2:select', function (e) {
                                    updateInputs();
                                });
                                updateInputs();
                            });
                            updateInputs();
                        });
                    </script>

                </div>
            </div>


        @elseif ($field->type == 'number')

            <!-- number -->
            <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                       for="{{
                    $fieldId }}">
                    {{ $field->name }}
                    @if ($field->required == 1)
                        <sup>*</sup>
                    @endif
                </label>
                <div>
                    <input id="{{ $fieldId }}"
                           name="{{ $fieldName }}"
                           type="number"
                           placeholder="{{ $field->name }}"
                           class="form-control input-md{{ $errorClass }}"
                           value="{{ $defaultValue }}">
                    <small id=""
                           class="form-text text-muted">{!! $field->help !!}</small>
                </div>
            </div>

        @else

            <!-- text -->
            <div class="ns-form-group {{ $requiredClass }} {{ $errorClass }}">
                <label class="ns-form-label {{ $errorClass }} {{ $errorClass }}"
                       for="{{
                    $fieldId }}">
                    {{ $field->name }}
                    @if ($field->required == 1)
                        <sup>*</sup>
                    @endif
                </label>
                <div>
                    <input id="{{ $fieldId }}"
                           name="{{ $fieldName }}"
                           type="text"
                           placeholder="{{ $field->name }}"
                           class="form-control input-md{{ $errorClass }}"
                           value="{{ $defaultValue }}">
                    <small id=""
                           class="form-text text-muted">{!! $field->help !!}</small>
                </div>
            </div>

        @endif
    @endforeach
@endif
