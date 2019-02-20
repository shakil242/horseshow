<div class="row">

    <div class="col-md-12">
        <div class="row">

            <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $model->reminderDays !!} <small>Days</small></h4></div>
            <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $model->reminderHours !!} <small>Hours</small></h4></div>
            <div class="col-md-4 p-2 bg-white border text-center" > <h4 class="mb-0">{!! $model->reminderMinutes !!} <small>Minutes</small></h4></div>
        </div>
    </div>
</div>

<div class="col-md-12 text-center mt-15">
    <a class="btn btn-primary text-right" href="javascript:void(0)" onclick="editReminder('{{$model->id}}','{{$model->show_id}}','{{$model->form_id}}','{{$model->reminderDays}}','{{$model->reminderHours}}','{{$model->reminderMinutes}}')"> Edit Reminder</a>

</div>
