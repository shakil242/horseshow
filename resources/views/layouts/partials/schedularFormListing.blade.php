<div class="invite-holder">
    {{--<a class="btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>--}}
    <div class="module-holer rr-datatable">
        <table id="crudTable2" class="table primary-table">
        @foreach($forms_collection as $form)
                <tr>
                    <td>{{getFormNamefromid($form->id)}}</td>
                    <td>
                        <a style="color: #ffffff" href="{{URL::to('master-template') }}/list/participant/scheduler/{{nxb_encode(12)}}/{{nxb_encode($form->id)}}/{{nxb_encode($assetId)}}/{{$associatedId}}/{{$isSubParticipant}}/{{$subId}}" class="btn btn-success" role="button">Manage</a></td>
                    </tr>
            @endforeach
        </table>
        </div>

</div>
