@extends('admin::layout')

@section('header')
    <link src="{{ url('assets/css/bootstrap-treeview.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        .tree-block {
            margin:0 10px 10px 10px;
            background: #ffffff;
            border-top: 2px solid;
            border-top-color: #3c8dbc;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
    </style>
@endsection


@section('content')
    <div id="tree" class="row" ></div>

    <?php
        $json = json_encode($result);
    ?>

    <script>
        $(document).ready(function() {
            var treeData = <?php echo $json; ?>;
            var tree = $('#tree');
            // var mainDiv = document.getElementById("tree");

            function setStats(obj) {
                let nodes = obj;
                for (var i = 0; i < nodes.length; i++) {
                    if (nodes[i].nodes == 0 && nodes[i].parent != 0) delete nodes[i]['nodes'];
                    if (nodes[i].hasOwnProperty('nodes') && nodes[i].nodes.length > 0) {
                        nodes[i].tags = ["" + (nodes[i].nodes.length - nodes[i].fields.length) + " subcategories / " + nodes[i].fields.length + " custom fields"];
                        nodes[i].nodes = setStats(nodes[i].nodes);
                    }
                }
                return nodes;
            }

            function setMainNodes(nodes) {
                for(var i = 0; i < nodes.length; i++) {
                    if (nodes[i].parent == 0) {
                        var outerdiv = document.createElement('div');
                        outerdiv.setAttribute("class", "col-sm-12 col-md-12 col-lg-12");
                        var div = document.createElement('div');
                        var mainNode = "mainNode-" + i;
                        div.setAttribute("id", mainNode);
                        div.setAttribute("class", "tree-block");

                        var parent = document.getElementById("tree");
                        outerdiv.appendChild(div);
                        parent.appendChild(outerdiv);

                        var node = $('#' + mainNode);
                        view([nodes[i]],node , mainNode);
                    }
                }
            }

            function view(data, node, parentNode) {
                node.treeview({
                    'data': data,
                    'multiSelect': true,
                    'levels': 1,
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
                    'onhoverColor': '#ecf0f5',
                    'showTags': true
                });
            }

            treeData = setStats(treeData);
            setMainNodes(treeData);

        });
    </script>

@endsection


@section('after_styles')

@endsection


@section('after_scripts')
    <script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
@endsection
