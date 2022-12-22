<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a class="link-secondary" href="{{route('sights.list',['category'=>$sight->category->id])}}">
            <i class="fas {{$sight->category->icon}}"></i>
            {{$sight->category->name}}
        </a> 
    </li>

    @if($sight->sub_category_id ?? 0 > 0)
        <li class="breadcrumb-item">
            <a class="link-secondary"  href="{{route('sights.list',['subcategory'=>$sight->sub_category_id])}}">
                {{$sight->subcategory->name}}
            </a>
        </li>
    @endif
</ol>
