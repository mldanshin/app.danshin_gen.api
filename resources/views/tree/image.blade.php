<?php
/**
 * @var App\View\Tree\Image $image
 */
?>
<image href="{{ $image->href }}"
    x="{{ $image->getPoint()->x }}"
    y="{{ $image->getPoint()->y }}"
    width="{{ $image->size->width }}"
    height="{{ $image->size->width }}"
    />