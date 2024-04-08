@extends('layout.app')
@section('content')
    <div class="card">
        <div class="card-body px-3">
            <div class="row">
                <div class="col-lg-3">
                    <div class="input-icon">
                        <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                        </span>
                        <input type="date" class="form-control" placeholder="Select a date" id="datepicker-icon-prepend" value="{{ date("Y-m-d") }}" name="date_from"/>
                      </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-icon">
                        <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                        </span>
                        <input type="date" class="form-control" placeholder="Select a date" id="datepicker-icon-prepend" value="{{ date("Y-m-d") }}" name="date_to"/>
                      </div>
                </div>
                <div class="col-lg-6">
                    <select name="warehouse" id="" class="form-select">
                        @foreach ($branches as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <table id="logRecordTable" class="table table-bordered " data-url="{{ route('bio.log') }}" style="width: 100%">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Section</td>
                        <td>Check In</td>
                        <td>Check Out</td>
                        <td>Duration</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('dist/libs/datatable/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/dataTables.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/buttons.dataTables.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/dataTables.buttons.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('dist/libs/datatable/js/responsive.bootstrap5.js') }}"></script>
<!-- Libs JS -->
<script src="{{ asset('dist/libs/list.js/dist/list.min.js') }}"></script>
<script>
    //  document.addEventListener("DOMContentLoaded", function () {
    // 	window.Litepicker && (new Litepicker({
    // 		element: document.getElementById('datepicker-icon-prepend'),
    // 		buttonText: {
    // 			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    //                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    //                             nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    //                 <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    // 		},
    // 	}));

        
    // });
    
    let logRecordTable;

    $('#logRecordTable').DataTable()

    const lodRecord = (date_from,date_to,warehouse)=>{
        $.ajax({
        type: "GET",
        url: $('#logRecordTable').attr("data-url"),
            data: {
                date_from,
                date_to,
                warehouse
            },
        }).done(function(data){
            
            logRecordTable = $("#logRecordTable").DataTable({
                bDestroy:true,
                layout: {
                    topStart: {
                            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                        }
                    },
                data: data,
                columns: [
                    { data: 'fullname', },
                    { data: 'name', },
                    // { data: 'checklog', },
                    { data: 'on_duty', },
                    { data: 'off_duty', },
                    { 
                        data: null,
                        render:function(data){
                            return data.total_hours_minutes
                            // return `Day(s) <b>${data.total_days ?? 0}</b>, Hour(s) <b>${data.total_hours ?? 0}</b>`
                        }
                    },
                ],
            });
        });
    }
   
    lodRecord($("input[name=date_from]").val(),$("input[name=date_to]").val(),$("select[name=warehouse]").val())

    $("input[name=date_from],input[name=date_to],select[name=warehouse]").on("change", function () {
        lodRecord($("input[name=date_from]").val(),$("input[name=date_to]").val(),$("select[name=warehouse]").val())
    });

    // logRecordTable = $("#logRecordTable").DataTable({
    //     processing: true,
    //     serverSide: true,
    //     responsive: true,
      
    //     ajax: {
    //         url: $('#logRecordTable').attr("data-url"),
    //         data: function (d) {
    //             d.date_from = $("input[name=date_from]").val();
    //             d.date_to = $("input[name=date_to]").val();
    //             d.warehouse = $("select[name=warehouse]").val();
    //         },
    //     },
    //     layout: {
    //         topStart: {
    //             buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    //         }
    //     },
    //     columns: [
    //         { data: 'fullname', },
    //         { data: 'name', },
    //         { data: 'checklog', },
    //         { data: 'TimeStampLog', },
    //     ],
    // });


        


        // $("#datepicker-icon-prepend").on("change", function() {
        //     // `event.date` is the new date
        //     // `event.oldDate` is the previous date
        //    alert($("input[name=date_from]").val());
        //     logRecordTable.draw();
        // });
</script>
@endsection