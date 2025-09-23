# Wordpress image Position

Expand the media library to allow you to set a focal point, which can be used to display an in image in cover while maintaining the needed parts.

## Installation

Add the plugin to your plugins folder

## Usage
```bladehtml
<div class="team__member__header">
    @php
        $focalPoint = get_post_meta($item->image->id, 'bg_pos_desktop');
    @endphp
    <img
        src="{{ $item->image->url }}"
        srcset="{{ $item->image->srcset }}"
        alt="{{ $item->image->alt }}"
        style="{{ $focalPoint ? "object-position: {$focalPoint};" : '' }} {{ $objectFit ? "object-fit: {$objectFit};" : '' }} "
    >
</div>
```
