<?php
/**
 * @var App\View\Tree\Person $person;
 */
?>
<g class="tree-person-container">
    <rect class="@if ($person->isPersonTarget) {{ " tree-person-basic " }} @else {{ " tree-person " }} @endif"
        @if ($person->isPersonTarget) {!! "id=\"tree-person-basic\"" !!} @else {{ "" }} @endif
        x="{{ $person->getPoint()->x }}"
        y="{{ $person->getPoint()->y }}"
        width="{{ $person->size->width }}"
        height="{{ $person->size->height }}"
        />
    @include("tree.text", ["text" => $person->surname])
    @includeWhen($person->oldSurname, "tree.text", ["text" => $person->oldSurname])
    @include("tree.text", ["text" => $person->name])
    @include("tree.text", ["text" => $person->patronymic])
    @include("tree.text", ["text" => $person->periodLive])
    @empty(!$person->linkCard)
        @include("tree.link", ["link" => $person->linkCard, "class" => "tree__button-show-person"])
    @endempty
    @empty(!$person->linkTree)
        @include("tree.link", ["link" => $person->linkTree, "class" => "tree__button-show-tree"])
    @endempty
</g>