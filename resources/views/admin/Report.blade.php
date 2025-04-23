@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Campaign Report <i class="fa fa-list ms-2"></i></h5>
                <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            
            <div class="card-body thm-card-body">
                <form method="GET" action="{{ route('campaign.assign.filter') }}" class="row g-3 contact-section">
                    <div class="col-md-6">
                        <label  class="form-label">Report For:</label>
                        <select name="" class="form-control selectpicker" title="-- Select Here --" multiple required>
                            <option>Campaign</option>
                            <option>Hoarding</option>
                        </select>
                    </div>
                    
                    
                    
                    <div class="col-md-6">
                        <label for="" class="form-label">Campaign Name:</label>
                        <select name="" class="form-control selectpicker"  data-live-search="true" title="-- Select Campaigns --" data-selected-text-format="count > 4" multiple required>
                            <option value="all">Select All</option>
                            <option>Swachh Bharat</option>
                            <option>Beti Bachao</option>
                            <option>Har Ghar Ujjwala</option>
                            <option>Namami Gange</option>
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="" class="form-label">Hoarding ID:</label>
                        <select name="" class="form-control selectpicker" data-live-search="true" title="-- Select Hoardings --" data-selected-text-format="count > 4" multiple required>
                            <option value="all">Select All</option>
                            <option>Hoarding 1</option>
                            <option>Hoarding 2</option>
                            <option>Hoarding 3</option>
                            <option>Hoarding 4</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="" class="form-label">From Date:</label>
                        <input type="date" class="form-control">
                    </div>
                 
                 
                    <div class="col-md-4">
                        <label for="" class="form-label">To Date:</label>
                        <input type="date" class="form-control">
                    </div>
                    
                     <div class="col-md-4">
                        <label for="" class="form-label" >State:</label>
                        <select name="" class="form-select state" >
                           <option value=""></option>
                        </select>
                    </div>
                     <div class="col-md-4">
                        <label for="" class="form-label">City:</label>
                        <select name="" class="form-select city" >
                           <option value=""></option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="" class="form-label">Block:</label>
                        <select name="" class="form-select " >
                           <option value="all">Option 1</option>
                           <option value="1">Option 2</option>
                           <option value="2">Option 3</option>
                        </select>

                    </div>
            
                    <div class="col-12">
                        <button class="btn btn-primary" name="search" value="1">Search<i class="fa fa-search ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-lg-12 mx-auto">
      <div class="card thm-card">
          <div class="thm-card-head bg-gradient-danger card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title thm-card-title ms-0">Campaign Details<i class="fa fa-info ms-2"></i></h5>
              <button class="btn btn-sm btn-light text-primary" id="exportBtn">Export To Excel<i class="fa fa-download ms-2"></i></button>
          </div>
          <div class="card-body thm-card-body">
            <div class="table-responsive">
                <table class="table table-bordered thm-tbl" id="reportTable">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Hoarding ID</th>
                                <th>Campaign Name</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>State</th>
                                <th>District</th>
                                <th>District Area</th>
                                <th>Location Address</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                            <tr>
                               <td>1.</td>
                               <td>HC50093468</td>
                               <td>Swachh Bharat Campaign</td>
                               <td>BillBoard</td>
                               <td>Light hoarding Premium</td>
                               <td>01-02-2025</td>
                               <td>02-04-2025</td>
                               <td>Chattisgarh</td>
                               <td>Raipur</td>
                               <td>DHARAMPURA</td>
                               <td>AIRPORT ROAD PTS CHOWK, FCNG AIRPORT</td>
                            </tr>
                            
                            <tr>
                               <td>2.</td>
                               <td>HC50093558</td>
                               <td>Namami Gange</td>
                               <td>Unipole</td>
                               <td>Light Unipole</td>
                               <td>01-02-2025</td>
                               <td>02-04-2025</td>
                               <td>Chattisgarh</td>
                               <td>Raipur</td>
                               <td>MANA</td>
                               <td>MANA BASTI EXPRESSWAY, FCNG RAIPUR</td>
                            </tr>
                           
                        </tbody>
                    </table>
             <!--<button type="submit" class="btn btn-primary">Assign Selected</button>-->
            </div>
          </div>
      </div>
      
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    document.getElementById('exportBtn').addEventListener('click', function () {
      let table = document.getElementById('reportTable');
      let workbook = XLSX.utils.table_to_book(table, { sheet: "Report" });
      XLSX.writeFile(workbook, 'Campaign_Report.xlsx');
    });
</script>
@endsection


