<?php
/**
 * @var App\View\Tree\Text $text
 */
?>
<text class="tree-person-text tree-font"
    x="{{ $text->getPoint()->x }}"
    y="{{ $text->getPoint()->y }}">
    {{ $text->content }}
</text>