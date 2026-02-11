@extends('layouts.admin.default')
@push('styles')
<style>

.fc .fc-daygrid-event-harness {
    margin-bottom: 2px;
}

.holiday-label {
  font-weight: bold;
  color: #8dff0aff; /* dark red */
  font-size: 6px;
  padding: 2px 4px;
  border-radius: 4px;
  margin-bottom: 2px;
  display: block;
}

.fc-holiday {
    font-size: 8px !important;
    white-space: normal !important;   /* allow text to wrap */
    word-wrap: break-word !important; /* break long words if needed */
    overflow-wrap: break-word !important;
    line-height: 1.2;                 /* optional: adjust line spacing */
}



/* Time slots taller */
.fc .fc-timegrid .fc-timegrid-slot {
    height: 120px !important;
}

.fc .fc-timegrid-slot-label,
.fc .fc-timegrid-slot-label-frame {
    height: 60px !important;
    line-height: 60px;
}

/* Custom event content */
.fc-event-custom {
    font-size: 8px;
    line-height: 1.3;
    padding: 2px 4px;
}

.fc-event-custom .fc-event-time {
    font-weight: 600;
}

.fc-event-custom .fc-event-title {
    font-weight: 700;
}

.fc-event-custom .fc-event-customer-name,
.fc-event-custom .fc-event-customer-phone {
    font-size: 8px;
    opacity: 0.9;
}
.fc-event-customer-info, .fc-event-info{
 display: flex;
 gap: 5px;
 text-align: center;
 justify-content: center;
}
.fc-event-status {
  display: inline-block;
  font-size: 9px;
  font-weight: 700;
  text-transform: uppercase;
  background: rgba(255,255,255,0.3);
  padding: 1px 4px;
  border-radius: 3px;
  margin-left: 4px;
}
.fc-month-summary {
  white-space: normal;
  line-height: 1.2;
}

.fc-month-summary .fc-event-title {
  font-size: 8px;
  font-weight: 600;
  word-break: break-word;
}

.fc-month-summary .fc-event-count {
  margin-top: 2px;
  font-size: 8px;
  background: rgba(255,255,255,0.85);
  color: #000;
  padding: 1px 6px;
  border-radius: 10px;
  display: inline-block;
}
/* Disable "grab" cursor on all events */
.fc-event {
  cursor: default !important;
}

/* Optional: if you want pointer on click instead */
.fc-event {
  cursor: pointer !important;
}

</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- row -->

    <div class="row">
        <div class="col-xl-9 col-xxl-12">
            <div class="card" id="external-events" >
                <div class="card-body">
                    <div id="calendar-skeleton" style="
                        position: absolute;
                        top: 0; left: 0;
                        width: 100%; height: 100%;
                        background-color: #fff;
                        z-index: 1000;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 18px;
                        color: #888;
                    ">
                        <div id="calendarLoading" class="d-flex justify-content-center align-items-center" style="height: 150px;">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2 text-muted small">Loading calendar...</div>
                            </div>
                        </div>
                    </div>
                    <div id="calendar" class="app-fullcalendar"></div>
                </div>
            </div>
        </div>
        <!-- BEGIN MODAL -->
        <div class="modal fade none-border" id="event-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add New Event</strong></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success save-event waves-effect waves-light">Create
                            event</button>

                        <button type="button" class="btn btn-danger delete-event waves-effect waves-light"
                            data-bs-toggle="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Add Category -->
        <div class="modal fade none-border" id="add-category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add a category</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label form-label">Category Name</label>
                                    <input class="form-control form-white mb-3 mb-sm-0" placeholder="Category Name"
                                        type="text" name="category-name">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label form-label">Choose Category Color</label>
                                    <select class="form-control  default-select" data-placeholder="Choose a color..."
                                        name="category-color">
                                        <option value="success">Success</option>
                                        <option value="danger">Danger</option>
                                        <option value="info">Info</option>
                                        <option value="pink">Pink</option>
                                        <option value="primary">Primary</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light waves-effect"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light save-category"
                            data-bs-toggle="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
