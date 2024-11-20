<div class="modal" id="showModalEmployee" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('bio.employee.store') }}" method="post" autocomplete="off">@csrf
          <input type="text" name="id" id="id" value="" class="d-none"/>
          <div class="col-lg-12 mb-4">
            <div class="row g-2">
                <!-- Date From Input -->
                <div class="col-lg-3">  
                      <input type="text" id="fullname" class="form-control" name="fullname" placeholder="(Ex:Surename,Given name)"/> 
                  </div>
                  <!-- Date To Input -->
                  <div class="col-lg-3">  
                      <input type="text" id="enrolid" class="form-control" name="enrolid" placeholder="Registered ID"/> 
                  </div>
                  <!-- Warehouse Select -->
                  <div class="col-lg-3">
                    <select id="warehouse" name="warehouse" class="form-select">
                        @foreach ($branches as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    <button type="reset" class="btn btn-seconday btn-block">Clear</button>
                </div>
            </div>
        </div>
        </form>
          <table id="employeeTable" class="table table-bordered table-hover" 
              data-url="{{ route('bio.employee') }}"
              data-delete="{{route('bio.employee.destroy')}}"
              style="width: 100%;font-size: 13px"
            >
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Registered ID</td>
                </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>