<!-- select2 from ajax -->
<div @include('admin::panel.inc.field_wrapper_attributes') >
	<label>{!! $field['label'] !!}</label>
	<?php $entity_model = $xPanel->model; ?>

	<input name="{{ $field['name'] }}" id="{{ $field['name'] }}" type="hidden"/>
	<div id="{{ $field['name'] }}_tree"></div>

	@if (isset($field['hint']))
		<p class="help-block">{!! $field['hint'] !!}</p>
	@endif
</div>

@if ($xPanel->checkIfFieldIsFirstOfItsType($field, $fields))
	@push('crud_fields_styles')
		<link type="text/css" href="{{ url('assets/plugins/fontawesome/css/solid.css') }}" rel="stylesheet">
		<link type="text/css" href="{{ url('assets/css/bootstrap-treeview.min.css') }}" rel="stylesheet">
	@endpush

	@push('crud_fields_scripts')
		<script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
	@endpush
@endif

@push('crud_fields_scripts')
	<script>
		function setSelected(data, id) {
			res = data.map(function(v) {
				if (parseInt(v.id) === id) {
					v.state.selected=true;
				}
				if (v.hasOwnProperty('nodes')) {
					v.nodes = setSelected(v.nodes,id);
				}
				return v;
			});
			return res;
		}
		jQuery(document).ready(function($) {
			var data = {!! $field['options'] !!};
			data = setSelected(data, {{ $field['value'] }});
			var tree = $('#{{ $field['name'] }}_tree');
			tree.treeview({
				'data': data,
				'multiSelect': false,
				'levels': 1,
				'collapseIcon': "fas fa-chevron-up",
				'expandIcon': "fas fa-chevron-down",
				'selectedIcon': "fas fa-check",
				'showTags': false
			});

			tree.treeview('getSelected').forEach(function(v) {
				tree.treeview('revealNode', [ v.nodeId, { silent: true } ]);
				$('#{{ $field['name'] }}').val(v.id);
			});

			tree.on('nodeSelected', function (event, node) {
				tree.treeview('expandNode', [node.nodeId, {levels: 2, silent: true}]);
				$('#{{ $field['name'] }}').val(node.id);
			});
			tree.on('nodeUnselected', function (event, node) {
				$('#{{ $field['name'] }}').val(null);
			});

		});
	</script>
@endpush
