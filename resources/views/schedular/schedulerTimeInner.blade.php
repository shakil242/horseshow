@if($updateRequest==0)
<div class="row scheduler-{{$row['scheduler_key']}} schedulerCon ">
@endif
    <div class="col-sm-offset-11 col-sm-1" style="margin-top: 20px;">
        <a style="float: right" href="javascript:void(0)" onclick="editScheduler('{{$row['scheduler_key']}}')" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
        <a href="javascript:void(0)" onclick="deleteScheduler($(this),'{{$row['scheduler_key']}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
    </div>

    <div class="table-responsive">
        <table class="table table-line-braker mt-10 custom-responsive-md">
            <tbody>
            <tr>
                <td width="180" scope="row">
                    {{post_value_or($m_s_fields,'selectClass','Classes')}}
                </td>
                <td class="pl-0" width="400">
                    {!! getClassNames($row['asset_id']) !!}
                </td>
                <td  class="pl-0" width="180">
                    {{post_value_or($m_s_fields,'selectScoreClass','Score From')}}
                </td>
                <td  class="pl-0" width="400">
                    {!!($row['score_from'] ? getClassNames($row['score_from']) : "N/A")  !!}
                </td>

            </tr>

            <tr>

                <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectDateAndTime','Scheduler Time')}}</td>
                <td class="pl-0" width="400">{{($row['restriction'] ? $row['restriction'] : "N/A")}}</td>
                <td class="pl-0" width="180">{{post_value_or($m_s_fields,'SelectBlockTime','Block Time')}}</td>
                <td class="pl-0" width="400">{{($row['block_time'] ? $row['block_time'] : "N/A")}}</td>
            </tr>
            <tr>

                <td class="pl-0" width="180">{{post_value_or($m_s_fields,'BlockTimeTitle','Block Time Title')}}</td>
                <td class="pl-0" width="400">{{($row['block_time_title'] ? $row['block_time_title'] : "N/A")}}</td>
                <td class="pl-0" width="180">{{post_value_or($m_s_fields,'restrictRiderToBookRides','Rider restrcited to Book Rides?')}}</td>
                <td class="pl-0" width="400">{{ ($row['is_rider_restricted']==1) ? 'Yes' : "N/A" }}</td>


            </tr>
            <tr>

                <td class="pl-0" width="180">{{post_value_or($m_s_fields,'MultipleTimeSelection','Multiple Time Selection')}}</td>
                <td class="pl-0" width="400">{{ ($row['is_multiple_selection']==1) ? 'Yes' : "N/A" }}</td>
                <td class="pl-0" width="180">Qualifying</td>
                <td class="pl-0" width="400">{{ ($row['qualifing_check']==1) ? "($)".$row['qualifing_price'] : "N/A" }}</td>


            </tr>
            </tbody>
        </table>
    </div>
    @if($updateRequest==0)
</div>
@endif
