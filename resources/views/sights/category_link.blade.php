<a class="link-secondary" href="{{route('sightCategory',$sight->category->id)}}">
    <i class="fas {{$sight->category->icon}}"></i>
    {{$sight->category->name}}
    @if($sight->sub_category_id ?? 0 > 0)
        ({{$sight->subcategory->name}})
    @endif
</a> 
