@if ($paginator->lastPage() > 1)
<ul class="pagination">
    <li class="paginate_button previous {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}" id="response_history_previous">
        <a href="{{ $paginator->url(1) }}" aria-controls="response_history" data-dt-idx="0" tabindex="0">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
        </a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="paginate_button {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
             <a href="{{ $paginator->url($i) }}" aria-controls="response_history" data-dt-idx="{{$i}}" tabindex="0">{{ $i }}</a>
        </li>
    @endfor
    <li class="paginate_button next {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}" id="response_history_next">
        <a href="{{ $paginator->url($paginator->currentPage()+1) }}" aria-controls="response_history" data-dt-idx="8" tabindex="0">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
    </li>
</ul>
@endif