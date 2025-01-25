<?php
/**
 * @var App\Models\Tree\Family $family
 */
?>
@include("tree.person", ["person" => $family->person])
@foreach ($family->marriage as $item)
    @include("tree.person", ["person" => $item])
@endforeach
@foreach ($family->children as $item)
    @include("tree.family", [
        "family" => $item
    ])
@endforeach