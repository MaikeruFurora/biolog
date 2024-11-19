@extends('layout.app')
@section('content') 
    <div class="card">
        <div class="card-body px-3">
            <div class="d-flex justify-between align-items-center">
                <!-- Left Section: Inputs -->
                <div class="col-lg-6 mb-4">
                    <div class="row g-3">
                        <!-- Date From Input -->
                        <div class="col-lg-4">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                        <path d="M16 3v4" />
                                        <path d="M8 3v4" />
                                        <path d="M4 11h16" />
                                        <path d="M11 15h1" />
                                        <path d="M12 15v3" />
                                    </svg>
                                </span>
                                <input type="date" id="date_from" class="form-control" name="date_from" value="{{ date('Y-m-d') }}" />
                            </div>
                        </div>
                        <!-- Date To Input -->
                        <div class="col-lg-4">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                        <path d="M16 3v4" />
                                        <path d="M8 3v4" />
                                        <path d="M4 11h16" />
                                        <path d="M11 15h1" />
                                        <path d="M12 15v3" />
                                    </svg>
                                </span>
                                <input type="date" id="date_to" class="form-control" name="date_to" value="{{ date('Y-m-d') }}" />
                            </div>
                        </div>
                        <!-- Warehouse Select -->
                        <div class="col-lg-4">
                            <select id="warehouse" name="warehouse" class="form-select">
                                @foreach ($branches as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            
                <!-- Right Section: Text -->
                <div class="col-lg-2 mb-4 ms-auto text-end">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-primary" id="btnEmployee" >
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Employee
                          </button>
                    </div>
                </div>
            </div>
            <table id="logRecordTable" class="table table-bordered" data-url="{{ route('bio.log') }}" style="width: 100%;font-size: 13px">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Section</td>
                        <td>Check log</td>
                        <td>Date & time log</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@include('component.employee-list') 
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

const overlay = document.getElementById('overlay');

// Show and hide the overlay on window load
window.onload = () => {
    showOverlay();
    setTimeout(hideOverlay, 1000);
};

// Utility functions for overlay visibility
const showOverlay = () => (overlay.style.display = 'block');
const hideOverlay = () => (overlay.style.display = 'none');

// Initialize DataTables globally 
$('#logRecordTable').DataTable() 
$('#employeeTable').DataTable()
// Helper function to initialize and reload a DataTable
const loadTableData = (tableSelector, params, url, columns) => {
    showOverlay();
    $.ajax({
        type: "GET",
        url: url,
        data: params,
    })
        .done((data) => {
            
            $(tableSelector).DataTable({
                bDestroy:true,
                layout: {
                    topStart: {
                            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                        }
                    },
                data: data,
                columns: columns,
            });
 
            hideOverlay();
        })
        .fail((jqXHR) => {
            hideOverlay();
            console.error(jqXHR.responseText);
        });
};

// Load initial log records
const logRecordParams = {
    date_from: $("input[name=date_from]").val(),
    date_to: $("input[name=date_to]").val(),
    warehouse: $("select[name=warehouse]").val(),
};

loadTableData(
    '#logRecordTable',
    logRecordParams,
    $('#logRecordTable').data('url'),
    [
        { data: 'fullname' },
        { data: 'name' },
        { data: 'checklog' },
        { data: 'TimeStampLog' },
    ]
);

// Reload log records on filter change
$("input[name=date_from], input[name=date_to], select[name=warehouse]").on("change", () => {
    const updatedParams = {
        date_from: $("input[name=date_from]").val(),
        date_to: $("input[name=date_to]").val(),
        warehouse: $("select[name=warehouse]").val(),
    };

    loadTableData(
        '#logRecordTable',
        updatedParams,
        $('#logRecordTable').data('url'),
        [
            { data: 'fullname' },
            { data: 'name' },
            { data: 'checklog' },
            { data: 'TimeStampLog' },
        ]
    );
});


const loadEmployee = () => {
    const employeeParams = {
        warehouse: $("#showModalEmployee #warehouse").val(),
    };

    loadTableData(
        '#employeeTable',
        employeeParams,
        $('#employeeTable').data('url'),
        [
            { data: 'fullname' },
            { data: 'enrolid' },
        ]
    );
}

// Load employee data when button is clicked
$("#btnEmployee").on('click', () => {
    $("#showModalEmployee").modal('show');
    loadEmployee();
});

$("#showModalEmployee #warehouse").on('change', () => { loadEmployee(); });

$("#showModalEmployee form").on('submit', (e) => {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: $("#showModalEmployee form").attr('action'),
        data: $("#showModalEmployee form").serialize(),
        beforeSend: () => {
            showOverlay();
        }, 
       
    }).done( function(data) {
            hideOverlay();
            $("#showModalEmployee form")[0].reset();
            loadEmployee(); 
    }).fail(function(jqXHR) {
        hideOverlay();
        const response = jqXHR.responseJSON;
        if (response && response.errors) {
            let errorMessages = "";
            for (const field in response.errors) {
                if (response.errors.hasOwnProperty(field)) {
                    errorMessages += response.errors[field].join(" ") + "\n";
                }
            }
            alert(errorMessages.trim());
        } else {
            alert("An unexpected error occurred.");
        } 
    });
});

 
</script>
@endsection