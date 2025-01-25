<?php
/**
 * @var App\View\Tree\Link $link
 * @var string $class
 */
?>
<image class="button icon-sm {{ $class }}"
    href="{{ $link->imagePath }}"
    x="{{ $link->getPoint()->x }}"
    y="{{ $link->getPoint()->y }}"
    width="{{ $link->size->width }}"
    height="{{ $link->size->width }}"
    data-person="{{ $link->personId }}"
    data-path="{{ $link->path }}"
/>