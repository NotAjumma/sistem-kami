@extends('layouts.admin.default')

@push('styles')
<style>
    /* --- your original styles --- */
    .calendar {
        border: 1px solid #ccc;
    }

    .calendar td,
    .calendar th {
        height: 60px;
        text-align: center !important;
        vertical-align: middle;
    }

    table.calendar thead tr th {
        text-align: center !important;
    }

    .today {
        background-color: rgba(var(--bs-secondary-rgb)) !important;
        color: #fff !important;
    }

    .selected {
        background-color: var(--primary) !important;
        color: #fff !important;
    }

    .off-day {
        background-color: rgb(205, 205, 207) !important;
        color: #6c757d !important;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .booked-day {
        background-color: rgba(var(--bs-success-rgb)) !important;
        color: #fff !important;
        cursor: not-allowed;
    }

    .past-day {
        background-color: rgb(205, 205, 207) !important;
        color: #6c757d !important;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .limit-reached {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        cursor: not-allowed !important;
    }

    .calendar .highlight {
        background-color: #f0f0f0;
    }

    .time-slot {
        border: 1px solid #ddd;
        padding: 8px;
        margin-bottom: 5px;
        cursor: pointer;
    }

    .time-slot.selected {
        background-color: #000;
        color: #fff;
    }

    .calendar-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    button:disabled {
        background-color: rgb(205, 205, 207) !important;
        color: #6c757d !important;
        border-color: rgb(205, 205, 207) !important;
        cursor: not-allowed !important;
    }

    .legend-box {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
        vertical-align: middle;
        border-radius: 4px;
    }

    .about-property ul {
        margin-left: 20px;
        list-style: disc;
    }

    .about-property p {
        margin-bottom: 10px;
    }

    ol {
        display: block !important;
        list-style-type: lower-roman !important;
        margin: 1em 0 !important;
        padding-left: 40px !important;
    }

    li {
        list-style-type: inherit !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Booking</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form action="{{ route('organizer.business.booking.update', $booking->id) }}" method="POST">
                    @csrf
                    <div class="row">

                        <!-- Customer Name -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Customer Name" 
                                value="{{ old('name', $booking->participant->name ?? '') }}" required>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer Whatsapp Number</label>
                            <input type="text" class="form-control" name="phone" placeholder="Whatsapp Number" 
                                value="{{ old('phone', $booking->participant->phone ?? '') }}" required>
                        </div>

                        <!-- Package -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Package</label>
                            <select class="form-select" name="package_id" id="packageSelect" required>
                                <option value="">Choose a package</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}"
                                        {{ old('package_id', $booking->package_id ?? '') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Calendar Section -->
                        <div class="mb-3 col-md-12">
                            <div id="calendarSection" class="d-none">
                                <hr class="mb-4" />
                                <div id="calendarLoading" class="d-none d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="mt-2 text-muted small">Loading calendar time slots...</div>
                                    </div>
                                </div>
                                <div id="calendarContent" class="d-none">
                                    <!-- Legend -->
                                    <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select a Date</h6>
                                    <div class="mt-0">
                                        <h5 class="fw-semibold mb-1">Calendar Indicator:</h5>
                                        <ul class="list-inline row gx-2 gy-2">
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box bg-secondary"></span> Today</li>
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box bg-success"></span> Booked Date</li>
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Not Available</li>
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box bg-primary"></span> Selected Date</li>
                                        </ul>
                                    </div>

                                    <!-- Navigation -->
                                    <div class="calendar-navigation mt-3">
                                        <div class="row g-2 col-12">
                                            <div class="col-12 col-md-12">
                                                <button id="prevMonth" class="btn btn-primary w-100" style="display:flex; justify-content:center;">
                                                    <i class="fa-regular fa-square-caret-left me-2"></i> Previous
                                                </button>
                                            </div>
                                            <div class="col-12 col-md-12 d-flex gap-2">
                                                <select id="monthSelect" class="form-select w-50"></select>
                                                <select id="yearSelect" class="form-select w-50"></select>
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <div class="d-flex flex-column flex-md-row gap-2">
                                                    <button id="todayBtn" class="btn btn-secondary w-100">Today</button>
                                                    <button id="nextMonth" class="btn btn-primary w-100" style="display:flex; justify-content:center;">
                                                        Next <i class="fa-regular fa-square-caret-right ms-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="calendar table table-bordered mb-3">
                                        <thead>
                                            <tr>
                                                <th>Mo</th>
                                                <th>Tu</th>
                                                <th>We</th>
                                                <th>Th</th>
                                                <th>Fr</th>
                                                <th>Sa</th>
                                                <th>Su</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendarBody"></tbody>
                                    </table>

                                    <!-- Time Slots -->
                                    <div id="timeSlots" class="d-none">
                                        <h6 class="fw-semibold mb-2">Select Time Slots</h6>
                                        <ul class="list-inline row gx-2 gy-2">
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box bg-success"></span> Booked Time</li>
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Available</li>
                                            <li class="list-inline-item col-6 col-md-auto"><span class="legend-box bg-primary"></span> Selected Date</li>
                                        </ul>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-bordered text-center align-middle" id="slotTable">
                                                <thead><tr id="slotHeader"><th></th></tr></thead>
                                                <tbody id="slotBody"></tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Add-ons -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Add Ons</label>
                            <div id="addonsContainer" class="border rounded p-3">
                                <small class="text-muted">Please select a package first</small>
                            </div>
                        </div>

                        <!-- Package & Price -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Package Base Price (RM)</label>
                            <input type="number" class="form-control" id="packagePrice" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Discount (RM)</label>
                            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', $booking->discount ?? 0) }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Final Price (RM)</label>
                            <input type="number" class="form-control" id="finalPrice" readonly>
                        </div>

                        <!-- Payment Type -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Payment Type</label>
                            <select class="form-select" id="paymentMethod" name="payment_type" required>
                                <option value="">Choose a payment type</option>
                                <option value="deposit" {{ (old('payment_type', $booking->payment_type ?? '')=='deposit')?'selected':'' }}>Deposit</option>
                                <option value="full_payment" {{ (old('payment_type', $booking->payment_type ?? '')=='full_payment')?'selected':'' }}>Full Payment</option>
                            </select>
                        </div>

                        <!-- Deposit Section -->
                        <div id="depositSection" class="{{ (old('payment_type', $booking->payment_type ?? '')=='deposit')?'':'d-none' }}">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Customer Pays Now (RM)</label>
                                <input type="number" class="form-control" id="customerPay" name="paid" value="{{ old('paid', $booking->paid ?? 0) }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Remaining Balance (RM)</label>
                                <input type="number" class="form-control" id="remainingBalance" readonly>
                            </div>
                        </div>

                        <!-- Reference -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Reference</label>
                            <select class="form-select" name="reference">
                                <option value="">Choose a Reference</option>
                                <option value="sales_a" {{ (old('reference', $booking->reference ?? '')=='sales_a')?'selected':'' }}>Sales A</option>
                                <option value="sales_b" {{ (old('reference', $booking->reference ?? '')=='sales_b')?'selected':'' }}>Sales B</option>
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="notes" rows="3">{{ old('notes', $booking->vendor_time_slot->notes ?? '') }}</textarea>
                        </div>

                        <input type="hidden" name="organizer_id" value="{{ $authUser->id }}">
                        <input type="hidden" name="selected_date" id="selected_date" value="{{ old('selected_date', $booking->vendor_time_slots->booked_date_start ?? '') }}">
                        <input type="hidden" name="selected_time" id="selected_time" value='@json(old("selected_time", $booking->vendor_time_slots->booked_time_start ?? []))'>
                    @php
$slots = $booking->vendorTimeSlots ?? collect();
@endphp

<input type="text" id="existing_date" 
       value="{{ $slots->isNotEmpty() ? $slots->first()->booked_date_start : '' }}">

<input type="text" id="existing_time" 
       value='@json($slots->map(fn($v) => ["id"=>$v->vendor_time_slot_id,"time"=>$v->booked_time_start]))'>

                    </div>

                    <button type="submit" class="btn btn-primary mt-5 w-100" id="bookNowBtn" disabled>{{ isset($isEdit)?'Update Booking':'Book Now' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Your original scripts for calendar, timeslots, pricing logic -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: @json(session('success')),
    showCancelButton: true,
    confirmButtonText: 'Send Receipt',
    cancelButtonText: 'OK',
    reverseButtons: true,
    allowOutsideClick: false,
    allowEscapeKey: false
}).then((result) => {
    if (result.isConfirmed) {
        window.open(@json(session('whatsapp_url')), "_blank");
    }
});
</script>
@endif
<script>
let calendarData = null;
let currentPackageId = null;
let currentDate = new Date();
let selectedTimes = [];
let requiredSlots = 1;

// DOM Elements
const calendarBody = document.getElementById("calendarBody");
const currentMonthDisplay = document.getElementById("currentMonth");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");
const timeSlotSection = document.getElementById("timeSlots");
const packageSelect = document.getElementById("packageSelect");
const monthSelect = document.getElementById("monthSelect");
const yearSelect = document.getElementById("yearSelect");
const packagePriceInput = document.getElementById('packagePrice');
const discountInput = document.getElementById('discount');
const finalPriceInput = document.getElementById('finalPrice');
const bookNowBtn = document.getElementById("bookNowBtn");
const selectedDateInput = document.getElementById("selected_date");
const selectedTimeInput = document.getElementById("selected_time");
const addonsContainer = document.getElementById('addonsContainer');
const paymentMethodSelect = document.getElementById('paymentMethod');
const depositSection = document.getElementById('depositSection');
const customerPayInput = document.getElementById('customerPay');
const remainingBalanceInput = document.getElementById('remainingBalance');
const maxBookingOffset = @json($package->max_booking_year_offset ?? 2);
const packages = @json($packages);

// --- UTILITY FUNCTIONS ---
function time24To12(time24) {
    const [h, m] = time24.split(':').map(Number);
    const ampm = h >= 12 ? 'PM' : 'AM';
    let hour12 = h % 12;
    if (hour12 === 0) hour12 = 12;
    return `${hour12}:${String(m).padStart(2,'0')} ${ampm}`;
}

function time12To24(time12) {
    const [time, modifier] = time12.split(' ');
    let [hours, minutes] = time.split(':').map(Number);
    if (modifier === 'PM' && hours !== 12) hours += 12;
    if (modifier === 'AM' && hours === 12) hours = 0;
    return `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:00`;
}

// --- FETCH CALENDAR ---
function fetchCalendarData(packageId) {
    const calendarSection = document.getElementById('calendarSection');
    const loading = document.getElementById('calendarLoading');
    const content = document.getElementById('calendarContent');

    calendarSection.classList.remove('d-none');
    loading.classList.remove('d-none');
    content.classList.add('d-none');

    return fetch(`/organizer/business/packages/${packageId}/calendar-data`)
        .then(res => res.json())
        .then(data => {
            calendarData = data;
            currentPackageId = packageId;
            renderCalendar(currentDate, calendarData, currentPackageId);
            loading.classList.add('d-none');
            content.classList.remove('d-none');
        })
        .catch(err => {
            console.error(err);
            loading.classList.add('d-none');
            alert('Failed to load calendar');
        });
}

// --- RENDER CALENDAR ---
function renderCalendar(date, data, packageId) {
    calendarBody.innerHTML = '';
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const firstWeekday = (firstDay.getDay() + 6) % 7; // Monday=0
    const totalDays = lastDay.getDate();

    const vendorOffDays = data.offDays;
    const fullyBookedDates = data.fullyBookedDates.map(b => b.date_start);
    const limitReachedDays = data.limitReachedDays;
    const weekRangeBlock = data.weekRangeBlock;

    const today = new Date();
    today.setHours(0,0,0,0);

    let row = document.createElement("tr");
    let dayCount = 0;

    for (let i=0; i<firstWeekday; i++) { row.appendChild(document.createElement("td")); dayCount++; }

    for (let day=1; day<=totalDays; day++) {
        const td = document.createElement("td");
        td.textContent = day;
        const currentLoopDate = new Date(year, month, day);
        currentLoopDate.setHours(0,0,0,0);
        const formattedDate = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

        // classes
        if (day === today.getDate() && month === today.getMonth() && year===today.getFullYear()) td.classList.add("today");
        if (fullyBookedDates.includes(formattedDate)) { td.classList.add("booked-day"); td.title="Fully booked"; }
        else if (limitReachedDays.includes(formattedDate)) { td.classList.add("past-day"); td.title="Limit reached"; }
        else if (vendorOffDays.some(o => o.off_date === formattedDate)) { td.classList.add("off-day"); td.title="Off day"; }
        else if (currentLoopDate < today) { td.classList.add("past-day"); td.title="Cannot book past date"; }

        td.addEventListener("click", () => {
            calendarBody.querySelectorAll("td").forEach(c=>c.classList.remove("selected"));
            td.classList.add("selected");
            selectedDateInput.value = formattedDate;

            if (data.timeSlots.length>0) {
                renderTimeSlot(formattedDate, currentLoopDate, packageId, data.package.base_price)
                .then(()=> autoSelectBooking()); // auto-select after timeslots rendered
            }
        });

        row.appendChild(td);
        dayCount++;
        if (dayCount%7===0){ calendarBody.appendChild(row); row=document.createElement("tr"); }
    }

    while (dayCount%7!==0){ row.appendChild(document.createElement("td")); dayCount++; }
    calendarBody.appendChild(row);
}

// --- RENDER TIMESLOTS ---
function renderTimeSlot(date, currentLoopDate, packageId, base_price){
    return new Promise((resolve,reject)=>{
        const slotHeader = document.getElementById("slotHeader");
        const slotBody = document.getElementById("slotBody");
        const packagePriceInput = document.getElementById('packagePrice');

        packagePriceInput.value = base_price;
        selectedTimes = [];
        timeSlotSection.classList.remove("d-none");
        slotHeader.innerHTML = "<th></th>";
        slotBody.innerHTML = "<tr><td colspan='100%' class='text-center py-4'>Loading...</td></tr>";

        fetch(`/api/packages/${packageId}/available-slots?date=${date}`)
            .then(res=>res.json())
            .then(data=>{
                slotHeader.innerHTML="<th></th>"; slotBody.innerHTML="";
                if (!data.slots || data.slots.length===0){ resolve(); return; }

                data.slots.forEach(slot=>{
                    const tr = document.createElement("tr");
                    const courtCell = document.createElement("td"); courtCell.textContent=slot.court; courtCell.style.fontWeight="600"; tr.appendChild(courtCell);

                    slot.times.forEach(time=>{
                        const td=document.createElement("td"); td.classList.add("text-center");
                        const cb=document.createElement("input"); cb.type="checkbox"; cb.classList.add("form-check-input");
                        cb.value=`${slot.id}|${time}`;
                        cb.addEventListener("change", ()=>{
                            const slotObj={date,id:slot.id,time};
                            if(cb.checked){ selectedTimes.push(slotObj); td.classList.add("bg-primary","text-white"); }
                            else { selectedTimes=selectedTimes.filter(s=>!(s.date===slotObj.date && s.id===slotObj.id && s.time===slotObj.time)); td.classList.remove("bg-primary","text-white"); }
                            selectedTimeInput.value=JSON.stringify(selectedTimes);
                            updateFinalPrice();
                            checkTimeSlotSelected();
                        });
                        td.appendChild(cb); tr.appendChild(td);
                    });
                    slotBody.appendChild(tr);
                });
                resolve();
            })
            .catch(err=>{ console.error(err); resolve(); });
    });
}

// --- AUTO SELECT ---
function autoSelectBooking(){
    const existingDate=document.getElementById('existing_date').value;
    const existingTimes=JSON.parse(document.getElementById('existing_time').value || '[]');
    if(!existingDate || existingTimes.length===0) return;

    const day=parseInt(existingDate.split('-')[2]);
    const td=[...calendarBody.querySelectorAll('td')].find(c=>parseInt(c.textContent)===day);
    if(!td) return;

    td.click();

    // wait for checkboxes
    const interval=setInterval(()=>{
        const ready=existingTimes.every(sel=>document.querySelector(`input[value="${sel.id}|${time24To12(sel.time)}"]`));
        if(ready){
            existingTimes.forEach(sel=>{
                const cb=document.querySelector(`input[value="${sel.id}|${time24To12(sel.time)}"]`);
                if(cb){ cb.checked=true; cb.dispatchEvent(new Event('change')); }
            });
            clearInterval(interval);
        }
    },100);
}

// --- UPDATE FINAL PRICE ---
function updateFinalPrice(){
    let total=0;
    // base package price * slots selected
    const packageQty = Math.floor(selectedTimes.length/requiredSlots);
    total += (parseFloat(packagePriceInput.value)||0)*packageQty;

    // addon checkbox
    document.querySelectorAll('.addon-checkbox:checked').forEach(cb=> total+=parseFloat(cb.dataset.price||0));
    // addon qty
    document.querySelectorAll('.addon-qty-input').forEach(input=>{
        total+= (parseInt(input.value||0)) * parseFloat(input.dataset.price||0);
    });
    total -= parseFloat(discountInput.value||0);
    finalPriceInput.value = Math.max(total,0).toFixed(2);
    updateRemainingBalance();
}

// --- CHECK TIMESLOT SELECTION ---
function checkTimeSlotSelected(){
    if(selectedTimes.length>0) bookNowBtn.removeAttribute("disabled");
    else bookNowBtn.setAttribute("disabled",true);
}

// --- REMAINING BALANCE ---
function updateRemainingBalance(){
    const finalPrice = parseFloat(finalPriceInput.value||0);
    const customerPay = parseFloat(customerPayInput.value||0);
    remainingBalanceInput.value=Math.max(finalPrice-customerPay,0);
}

// --- PACKAGE CHANGE ---
packageSelect.addEventListener('change', ()=>{
    const packageId=packageSelect.value;
    if(!packageId) return;

    // reset
    selectedTimes=[]; selectedDateInput.value=''; selectedTimeInput.value=''; timeSlotSection.classList.add('d-none');

    // render addons
    const selectedPackage = packages.find(p=>p.id==packageId);
    requiredSlots = selectedPackage.package_slot_quantity||1;
    addonsContainer.innerHTML='';
    if(selectedPackage.addons && selectedPackage.addons.length>0){
        selectedPackage.addons.forEach(addon=>{
            if(addon.is_qty){
                addonsContainer.insertAdjacentHTML('beforeend',`
                    <div class="mb-3 addon-qty-wrapper">
                        <label>${addon.name} (RM ${parseFloat(addon.price).toFixed(2)})</label>
                        <input type="number" min="0" value="0" class="addon-qty-input" data-price="${addon.price}" id="addon_qty_${addon.id}">
                    </div>`);
            } else {
                addonsContainer.insertAdjacentHTML('beforeend',`
                    <div class="form-check mb-2">
                        <input type="checkbox" class="addon-checkbox" data-price="${addon.price}" id="addon_${addon.id}">
                        <label for="addon_${addon.id}">${addon.name} (RM ${parseFloat(addon.price).toFixed(2)})</label>
                    </div>`);
            }
        });
    }

    fetchCalendarData(packageId).then(()=> autoSelectBooking());
});

// --- ADDONS CHANGE LISTENERS ---
addonsContainer.addEventListener('input', updateFinalPrice);
addonsContainer.addEventListener('change', updateFinalPrice);

</script>



@endpush
