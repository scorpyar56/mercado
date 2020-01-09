@extends('admin::layout')

<?php
    use Illuminate\Support\Facades\DB;
    class main
    {
        public $mainId = 0;
        public $lang = null;
        public $results = null;

        public function __construct()
        {
            $this->lang = config('app.locale', session('language_code'));
        }

        public function getCat($parentId)
        {
            $result = [];

            $categories = DB::select('SELECT * FROM 999_lara.categories WHERE parent_id = ' . $parentId . ' AND translation_lang = "' . $this->lang . '"');

            foreach ($categories as $c) {
                $fields = DB::select('SELECT 999_lara.fields.id, 999_lara.fields.name FROM 999_lara.fields AS fields
                                INNER JOIN 999_lara.category_field AS catFields ON fields.id = catFields.field_id
                                INNER JOIN 999_lara.categories AS cats ON catFields.category_id = cats.id
                                WHERE cats.parent_id = ' . $c->id);

                if ($parentId == 0) {
                    $this->mainId = $c->id;
                    $url = 'https://market.unifun.com/admin/categories/' . $this->mainId . '/edit';
                } else {
                    $url = 'https://market.unifun.com/admin/categories/' . $this->mainId . '/subcategories/' . $c->id . '/edit';
                }

                $custom_fields = [];
                foreach ($fields as $f) {
                    $custom_fields[] = [
                        'id' => $f->id,
                        'text' => $f->name,
                        'href' => 'https://market.unifun.com/admin/custom_fields/' . $f->id . '/edit'
                    ];
                }

                $nodes = $this->getCat($c->id);
                if ($nodes) {
                    $result[] = [
                        'id' => $c->id,
                        'text' => $c->name,
                        'state' => [
                            'checked' => false,
                            'disabled' => false,
                            'expanded' => false,
                            'selected' => false
                        ],
                        'href' => $url,
                        'fields' => $custom_fields,
                        'nodes' => $nodes,
                        'tags' => 0
                    ];
                }
                else {
                    $result[] = [
                        'id' => $c->id,
                        'text' => $c->name,
                        'href' => $url,
                        'fields' => $custom_fields,
                    ];
                }
            }

            return $result;
        }
    }

?>

@section('header')
    <link src="{{ url('assets/css/bootstrap-treeview.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        #tree{
            margin-left: 15px;
            margin-right: 15px;
            background: #ffffff;
            border-top: 2px solid;
            border-top-color: #3c8dbc;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
    </style>
@endsection


@section('content')
    <div id="tree" style="border-top: 2px solid; border-top-color: #3c8dbc"></div>

    <?php
        $main = new main;
        $result = $main->getCat(0);
        $json = json_encode($result);
    ?>

    <script>
        $(document).ready(function() {
            var treeData = <?php echo $json; ?>;
            var tree = $('#tree');

            function setStats(obj) {
                let nodes = obj;
                for (var i = 0; i < nodes.length; i++) {
                    // if (nodes[i].nodes.length > 0) {
                    //     nodes[i].tags = ["" + nodes[i].nodes.length];
                    // }
                    if (nodes[i].hasOwnProperty('nodes')) {
                        nodes[i].tags = ["" + nodes[i].nodes.length];
                        nodes[i].nodes = setStats(nodes[i].nodes);
                    }
                }
                return nodes;
            }

            console.log(treeData);

            treeData = setStats(treeData);

            tree.treeview({
                'data': treeData,
                'multiSelect': true,
                'levels': 2,
                'color': 'black',
                'selectedBackColor': 'none',
                'selectedColor': '#222d32',
                'showBorder': false,
                'collapseIcon': "glyphicon glyphicon-minus",
                'expandIcon': "glyphicon glyphicon-plus",
                'showTags': true,
                'enableLinks': true,
                'backColor': '#ffffff',
                'color': '#222d32',
                'onhoverColor': 'none',
                'showTags': true
            });

            // var selected = tree.treeview('getSelected');
            // var selectedIDs = selected.map(function (value) {
            //     return value.id;
            // });
            //
            // var inps = selectedIDs.map(function (v) {
            //     return '<div id="' + v + '" ' +
            //         'style="display: hidden">';
            // });
            // tree.html(inps.join(''));

        });
    </script>

@endsection


@section('after_styles')

@endsection


@section('after_scripts')
    <script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
@endsection