@php($getParams = $sightList->filters([],[$filterName]))

@if(!empty($sightList->$filterName))
    <a title="вiдключити вiдбiр" href="{{route('sights.list',$getParams)}}" class="enabled-filter">
        {{trim($sightList->$filterName->name)}}
        &nbsp;
        <i class="fas fa-times-circle"></i></a>
    </a>
@endif