<?php
/**
 *@var App\View\Tree\Tree $tree
 */
?>
<svg xmlns="http://www.w3.org/2000/svg"
    width="{{ $tree->size->width }}"
    height="{{ $tree->size->height }}"
    >
    <style title="tree-style-svg">
        .tree-person {
            fill: white;
            stroke:#3b3a3a;
            stroke-width: {{ $tree->stylePerson->strokeWidth }};
        }
        .tree-person-basic {
            fill: #7B68EE;
            stroke:#3b3a3a;
        }
        .tree-font {
            font-size: {{ $tree->stylePerson->fontSize }}px;
            text-anchor: middle;
        }
        .parent-children-relation {
            stroke: #3b3a3a;
            stroke-width: 1;
        }
    </style>
    @include("tree.family-relation", [
        "family" => $tree->family
    ])
    @include("tree.family", [
        "family" => $tree->family
    ])
    <g id="marker-adding"></g>
</svg>