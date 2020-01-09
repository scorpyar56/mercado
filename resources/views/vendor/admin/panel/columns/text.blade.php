{{-- regular object attribute --}}
<?php $depth = $entry->depth-1;?>
<td><span class="tree-depth-{{ $depth }}"></span>{{ \Illuminate\Support\Str::limit(strip_tags($entry->{$column['name']}), 80, "[...]") }}</td>
