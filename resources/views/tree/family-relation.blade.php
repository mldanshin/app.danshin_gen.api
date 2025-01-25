<?php
/**
 * @var App\Models\Tree\Family $family
 */
?>
@includeWhen(
    $family->getParentRelation(),
    "tree.parent-children-relation",
    ["relation" => $family->getParentRelation()]
)
@foreach ($family->children as $item)
    @include("tree.family-relation", [
        "family" => $item
    ])
@endforeach