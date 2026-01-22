@extends('layouts.admin.default')
@push('styles')
     <style>
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
                <h4 class="card-title">Create new booking</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('organizer.business.booking.create.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Customer Name" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Customer Whatsapp Number</label>
                                <input type="text" class="form-control" name="whatsapp_number" placeholder="Whatsapp Number" required>
                            </div>
                            <!-- <div class="mb-3 col-md-6">
                                <label class="form-label">Customer Email (for send receipt thru email)</label>
                                <input type="text" class="form-control" name="email" placeholder="Email" required>
                            </div> -->
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Package</label>
                                <select class="form-select" name="package_id" id="packageSelect" required>
                                    <option value="">Choose a package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-12">
                                <div id="calendarSection" class="d-none">
                                    <hr class="mb-4" />

                                    <!-- 🔽 ALL your calendar HTML goes here 🔽 -->
                                    <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select a Date</h6>
                                    <!-- Legend -->
                                    <div class="mt-0">
                                        <h5 class="fw-semibold mb-1">Calendar Indicator:</h5>
                                        <ul class="list-inline row gx-2 gy-2">
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box bg-secondary"></span> Today
                                            </li>
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box bg-success"></span> Booked Date
                                            </li>
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Not
                                                Available
                                            </li>
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box bg-primary"></span> Selected Date
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Calendar Navigation Controls -->
                                    <div class="calendar-navigation mt-3">
                                        <div class="row g-2 col-12">
                                            <!-- Previous Button -->
                                            <div class="col-12 col-md-12">
                                                <button id="prevMonth" class="btn btn-primary w-100"
                                                    style="text-align: center; display: flex; justify-content: center;">
                                                    <div>
                                                        <i class="fa-regular fa-square-caret-left me-2"></i>
                                                    </div>
                                                    <div>Previous</div>
                                                </button>
                                            </div>

                                            <!-- Month & Year Selectors -->
                                            <div class="col-12 col-md-12 d-flex gap-2">
                                                <select id="monthSelect" class="form-select w-50" style="font-size: 1rem;">
                                                    <!-- Populated via JS -->
                                                </select>
                                                <select id="yearSelect" class="form-select w-50" style="font-size: 1rem;">
                                                    <!-- Populated via JS -->
                                                </select>
                                            </div>

                                            <!-- Today and Next Button -->
                                            <div class="col-12 col-md-12">
                                                <div class="d-flex flex-column flex-md-row gap-2">
                                                    <button id="todayBtn" class="btn btn-secondary w-100">Today</button>
                                                    <button id="nextMonth" class="btn btn-primary w-100"
                                                        style="text-align: center; display: flex; justify-content: center;">
                                                        <div>Next</div>
                                                        <div><i class="fa-regular fa-square-caret-right ms-2"></i></div>
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
                                                <th style="text-align:center !important;">Su</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendarBody">
                                            <!-- Calendar will be dynamically generated here -->
                                        </tbody>
                                    </table>

                                    <!-- Time Slots -->
                                    <div id="timeSlots" class="d-none">
                                        <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select Time Slots</h6>
                                        <!-- Legend -->
                                        <div class="mt-0">
                                            <h5 class="fw-semibold mb-1">Time Slots Indicator:</h5>
                                            <ul class="list-inline row gx-2 gy-2">
                                                <li class="list-inline-item col-6 col-md-auto">
                                                    <span class="legend-box bg-success"></span> Booked Time
                                                </li>
                                                <li class="list-inline-item col-6 col-md-auto">
                                                    <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span>
                                                    Available
                                                </li>
                                                <li class="list-inline-item col-6 col-md-auto">
                                                    <span class="legend-box bg-primary"></span> Selected Date
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="table-responsive mt-2" style="overflow-x: auto;">
                                            <table class="table table-bordered text-center align-middle" id="slotTable">
                                                <thead class="">
                                                    <tr id="slotHeader">
                                                        <th></th>
                                                        <!-- JS will append time slots here -->
                                                    </tr>
                                                </thead>
                                                <tbody id="slotBody">
                                                    <!-- JS will append courts + time slot checkboxes here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <hr class="mb-4" />
                                </div>
                            </div>

                           <div class="mb-3 col-md-6">
                                <label class="form-label">Package Base Price (RM)</label>
                                <input type="number" class="form-control" id="packagePrice" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Discount (RM)</label>
                                <input type="number" class="form-control" id="discount" name="discount" value="0">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Final Price (RM)</label>
                                <input type="number" class="form-control" id="finalPrice" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Payment Type</label>
                                <select class="form-select" id="paymentMethod" name="payment_type" required>
                                    <option value="">Choose a payment type</option>
                                    <option value="deposit">Deposit</option>
                                    <option value="full_payment">Full Payment</option>
                                </select>
                            </div>

                            <!-- Deposit Section -->
                            <div id="depositSection" class="d-none">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Customer Pays Now (RM)</label>
                                    <input type="number" class="form-control" id="customerPay" name="deposit_amount" value="0">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Remaining Balance (RM)</label>
                                    <input type="number" class="form-control" id="remainingBalance" readonly>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Reference</label>
                                <select class="form-select" name="reference">
                                    <option value="">Choose a Reference</option>
                                    <option value="sales_a">Sales A</option>
                                    <option value="sales_b">Sales B</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>
                            </div>

                            <input type="hidden" name="organizer_id" value="{{ $authUser->id }}">

                            <input type="hidden" name="selected_date" id="selected_date">
                            <input type="hidden" name="selected_time" id="selected_time">
                        </div>
                        <button type="submit" class="btn btn-primary mt-5 w-100" id="bookNowBtn" disabled>Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
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

    <!-- calendar script -->
    
    <script>
       
        const calendarBody = document.getElementById("calendarBody");
        const currentMonthDisplay = document.getElementById("currentMonth");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");
        const maxBookingOffset = @json($package->max_booking_year_offset ?? 2);
        // console.log(vendorOffDays);
        let currentDate = new Date();
        const timeSlotSection = document.getElementById("timeSlots");

        function renderCalendar(date, data, packageId) {
            const vendorTimeSlots      = data.timeSlots;
            const vendorOffDays        = data.offDays;
            const bookedVendorDates    = data.bookedDates;
            const fullyBookedDates     = data.fullyBookedDates;
            const limitReachedDays     = data.limitReachedDays;
            const weekRangeBlock       = data.weekRangeBlock;
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-indexed (0 = January)
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const firstWeekday = (firstDay.getDay() + 6) % 7; // Make Monday = 0
            const totalDays = lastDay.getDate();
            const offDaysFormatted = vendorOffDays.map(off => off.off_date);
            
            let bookedDatesFormatted = [];

            if (vendorTimeSlots.length > 0) {
                bookedDatesFormatted = [...new Set(fullyBookedDates.map(b => b.date_start))];
            } else {
                bookedDatesFormatted = [...new Set(bookedVendorDates.map(b => b.date_start))];
            }

            
            // Update header
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            monthSelect.value = month;
            yearSelect.value = year;

            // Clear previous calendar
            calendarBody.innerHTML = "";

            let row = document.createElement("tr");
            let dayCount = 0;

            // Fill blanks before 1st
            for (let i = 0; i < firstWeekday; i++) {
                row.appendChild(document.createElement("td"));
                dayCount++;
            }

            // Fill days
            for (let day = 1; day <= totalDays; day++) {
                const td = document.createElement("td");
                td.textContent = day;

                const currentLoopDate = new Date(year, month, day);
                const yyyy = currentLoopDate.getFullYear();
                const mm = String(currentLoopDate.getMonth() + 1).padStart(2, '0');
                const dd = String(currentLoopDate.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;

                // Highlight today
                const today = new Date();
                const maxDate = new Date(today.getFullYear() + maxBookingOffset, 11);
                today.setHours(0, 0, 0, 0); // Normalize time
                currentLoopDate.setHours(0, 0, 0, 0);
                if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    td.classList.add("today");
                }

                if (bookedDatesFormatted.includes(formattedDate)) {
                    td.classList.add("booked-day");
                    td.title = "Unavailable (Fully Booked)";
                } else if (limitReachedDays.includes(formattedDate)) {
                    td.classList.add("past-day");
                    td.title = "Booking limit reached for this week";
                } else if (offDaysFormatted.includes(formattedDate)) {
                    td.classList.add("off-day");
                    td.title = "Unavailable (Off Day)";
                } else if (currentLoopDate.getTime() < today.getTime()) {
                    td.classList.add("past-day");
                    td.title = "Cannot book past date";
                } else {
                    // Check if in a blocked week
                    const weekWithBooking = weekRangeBlock.find(range => {
                        const [start, end] = range.split(' - ');
                        return formattedDate >= start && formattedDate <= end;
                    });

                    if (weekWithBooking) {
                        if (bookedDatesFormatted.includes(formattedDate)) {
                            td.classList.add("booked-day");
                            td.title = "Unavailable (Fully Booked)";
                        } else {
                            td.classList.add("past-day");
                            td.title = "Booking limit reached for this week";
                        }
                    }
                }

                const isPrevMonthBeforeToday =
                    year < today.getFullYear() ||
                    (year === today.getFullYear() && month <= today.getMonth());

                prevMonthBtn.disabled = isPrevMonthBeforeToday;

                // Disable next button if going beyond maxDate (e.g. Dec 2027)
                const isNextMonthAfterLimit =
                    currentDate.getFullYear() > maxDate.getFullYear() ||
                    (currentDate.getFullYear() === maxDate.getFullYear() && currentDate.getMonth() >= maxDate.getMonth());

                nextMonthBtn.disabled = isNextMonthAfterLimit;

                td.addEventListener("click", () => {
                    document.querySelectorAll("#calendarBody td").forEach(cell => cell.classList.remove("selected"));
                    td.classList.add("selected");

                    // Log selected date in yyyy-mm-dd format
                    const selectedDate = new Date(year, month, day);
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    const formattedDate = `${yyyy}-${mm}-${dd}`;

                    // Log it (for debug)
                    console.log(formattedDate);

                    // Set the hidden input value
                    document.getElementById("selected_date").value = formattedDate;
                    if (vendorTimeSlots.length > 0) {
                        const shouldHide =
                            currentLoopDate.getTime() < today.getTime() ||
                            offDaysFormatted.includes(formattedDate) ||
                            limitReachedDays.includes(formattedDate);

                        if (shouldHide) {
                            timeSlotSection.classList.add("d-none");
                        } else {
                            timeSlotSection.classList.remove("d-none");
                            renderTimeSlot(formattedDate, currentLoopDate, packageId, data.package.base_price);
                        }
                    } else {
                        checkDateSelected(bookedDatesFormatted, offDaysFormatted, currentLoopDate, formattedDate);
                    }   
                });

                row.appendChild(td);
                dayCount++;

                if (dayCount % 7 === 0) {
                    calendarBody.appendChild(row);
                    row = document.createElement("tr");
                }
            }

            // Fill blanks after last day
            while (dayCount % 7 !== 0) {
                row.appendChild(document.createElement("td"));
                dayCount++;
            }
            calendarBody.appendChild(row);
        }

        const todayBtn = document.getElementById("todayBtn");

        todayBtn.addEventListener("click", () => {
            currentDate = new Date(); // Reset to today
            renderCalendar(currentDate);
            document.getElementById("selected_date").value = "";
            document.getElementById("bookNowBtn").setAttribute("disabled", true);
        });

        prevMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        // Initial render
        renderCalendar(currentDate);

        const selectedDateInput = document.getElementById('selected_date');
        const bookNowBtn        = document.getElementById('bookNowBtn');
        const selectedTimeInput = document.getElementById("selected_time");

        function checkDateSelected(bookedDatesFormatted, offDaysFormatted, currentLoopDate, formattedDate) {
            if (selectedDateInput.value && bookedDatesFormatted.includes(formattedDate)) {
                bookNowBtn.setAttribute('disabled', true);
            } else if (limitReachedDays.includes(formattedDate)) {
                bookNowBtn.setAttribute('disabled', true);
            } else if (offDaysFormatted.includes(formattedDate)) {
                bookNowBtn.setAttribute('disabled', true);
            } else if (currentLoopDate.getTime() < today.getTime()) {
                td.classList.add("past-day");
                td.title = "Cannot book past date";
            } else {
                bookNowBtn.removeAttribute('disabled');

            }
        }

        function checkTimeSlotSelected(currentLoopDate) {
            const selectedTimeInput = document.getElementById("selected_time");
            const bookNowBtn        = document.getElementById("bookNowBtn");

            // Parse JSON safely
            let selected = [];
            try {
                selected = JSON.parse(selectedTimeInput.value || "[]");
            } catch (e) {
                selected = [];
            }

            // Enable if there are selected slots, disable otherwise
            if (Array.isArray(selected) && selected.length > 0) {
                bookNowBtn.removeAttribute("disabled");
            } else {
                bookNowBtn.setAttribute("disabled", true);
            }
        }

    </script>
    <script>
        const monthSelect = document.getElementById("monthSelect");
        const yearSelect = document.getElementById("yearSelect");
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const today = new Date();
        // let currentDate = new Date(); // This will be updated when user changes month/year

        // Populate year dropdown (current year to next 2 years)
        const currentYear = today.getFullYear();
        for (let y = currentYear; y <= currentYear + maxBookingOffset; y++) {
            const option = document.createElement("option");
            option.value = y;
            option.textContent = y;
            yearSelect.appendChild(option);
        }

        // Function to populate month dropdown based on selected year
        function populateMonths(selectedYear) {
            monthSelect.innerHTML = ""; // Clear previous options

            monthNames.forEach((name, index) => {
                const option = document.createElement("option");
                option.value = index;
                option.textContent = name;

                if (selectedYear == today.getFullYear() && index < today.getMonth()) {
                    option.disabled = true;
                    option.style.color = "#ccc";
                }

                monthSelect.appendChild(option);
            });
        }

        // Set default selections
        yearSelect.value = currentDate.getFullYear();
        populateMonths(currentDate.getFullYear());
        monthSelect.value = currentDate.getMonth();

        // Event listener for year change
        yearSelect.addEventListener("change", () => {
            const selectedYear = parseInt(yearSelect.value);
            currentDate.setFullYear(selectedYear);

            populateMonths(selectedYear);

            // If current selected month is now disabled, move to current month
            if (
                selectedYear === today.getFullYear() &&
                parseInt(monthSelect.value) < today.getMonth()
            ) {
                monthSelect.value = today.getMonth();
                currentDate.setMonth(today.getMonth());
            }

            renderCalendar(currentDate);
        });

        // Event listener for month change
        monthSelect.addEventListener("change", () => {
            const selectedMonth = parseInt(monthSelect.value);
            currentDate.setMonth(selectedMonth);
            renderCalendar(currentDate);
        });
    </script>

    <!-- END Calendar script -->


    <!-- Time Slot script -->
    <script>
        let selectedTimes = [];

        function renderTimeSlot(date, currentLoopDate, packageId, base_price) {
            console.log("Render time slot for:", date);
            const slotHeader = document.getElementById("slotHeader");
            const slotBody = document.getElementById("slotBody");
            const selectedTimeInput = document.getElementById("selected_time");

            const packagePriceInput = document.getElementById('packagePrice');
            const finalPriceInput = document.getElementById('finalPrice');
            const discount = document.getElementById('discount');
            packagePriceInput.value = 0;
            finalPriceInput.value = 0;
            discount.value = 0;
            // Array to store all selected time objects
            selectedTimes = [];
            // Always show the table section and clear previous content
            timeSlotSection.classList.remove("d-none");
            slotHeader.innerHTML = "<th></th>";
            slotBody.innerHTML = `
            <tr>
                <td colspan="100%" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 text-muted small">Loading available time slots...</div>
                </td>
            </tr>
        `;

            fetch(`/api/packages/${packageId}/available-slots?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    // Clear old content again after loading
                    slotHeader.innerHTML = "<th></th>";
                    slotBody.innerHTML = "";

                    // Handle no slots
                    if (!data.slots || data.slots.length === 0) {
                        slotBody.innerHTML = `
                        <tr>
                            <td colspan="100%" class="text-muted py-3">
                                No available time slots for this date.
                            </td>
                        </tr>`;
                        return;
                    }

                    // Add time header
                    const timeLabels = data.slots[0].times;
                    timeLabels.forEach(time => {
                        const [hourPart, ampm] = time.split(" "); // e.g. "9:00 AM" -> ["9:00", "AM"]
                        const th = document.createElement("th");
                        th.innerHTML = `<div style="font-size: 15px;" class="text-muted">${hourPart}</div><div style="font-size: 15px;" class="text-muted">${ampm || ""}</div>`;
                        th.style.lineHeight = "1.1";
                        th.style.padding = "10px";
                        slotHeader.appendChild(th);
                    });


                    // Create table body rows
                    // Build each slot row
                    data.slots.forEach(slot => {
                        const tr = document.createElement("tr");

                        // Court / Slot Name
                        const courtCell = document.createElement("td");
                        courtCell.textContent = slot.court;
                        courtCell.style.fontWeight = "600";
                        tr.appendChild(courtCell);

                        // Each time column
                        slot.times.forEach(time => {
                            const td = document.createElement("td");
                            td.classList.add("p-2", "text-center");

                            const checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.classList.add("form-check-input");
                            checkbox.value = `${slot.id}|${time}`;

                            // Mark booked slots (disable + green highlight)
                            if (slot.bookedTimes && slot.bookedTimes.includes(time)) {
                                checkbox.disabled = true;
                                td.classList.add("bg-success", "bg-opacity-50");
                            }

                            // On user selection
                            checkbox.addEventListener("change", () => {
                                const slotObj = { date, id: slot.id, time };

                                if (checkbox.checked) {
                                    td.classList.add("bg-primary", "text-white");
                                    selectedTimes.push(slotObj);
                                } else {
                                    td.classList.remove("bg-primary", "text-white");
                                    selectedTimes = selectedTimes.filter(
                                        s => !(s.date === slotObj.date && s.id === slotObj.id && s.time === slotObj.time)
                                    );
                                }

                                document.getElementById('packagePrice').value = base_price;
                                updateFinalPrice();
                                selectedTimeInput.value = JSON.stringify(selectedTimes);
                                checkTimeSlotSelected(currentLoopDate);
                                console.log("Selected times:", selectedTimes);
                            });

                            td.appendChild(checkbox);
                            tr.appendChild(td);
                        });

                        slotBody.appendChild(tr);
                    });
                })
                .catch(err => {
                    console.error("Error loading time slots:", err);
                    slotBody.innerHTML = `
                    <tr>
                        <td colspan="100%" class="text-danger py-3">
                            Failed to load time slots. Please try again.
                        </td>
                    </tr>`;
                });
        }

    </script>
    <script>
        const packagePriceInput = document.getElementById('packagePrice');
        const discountInput = document.getElementById('discount');
        const finalPriceInput = document.getElementById('finalPrice');
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const depositSection = document.getElementById('depositSection');
        const customerPayInput = document.getElementById('customerPay');
        const remainingBalanceInput = document.getElementById('remainingBalance');
        document.getElementById('packageSelect').addEventListener('change', function () {
            const packageId = this.value;

            if (!packageId) {
                document.getElementById('calendarSection').classList.add('d-none');
                packagePriceInput.value = '';
                finalPriceInput.value = '';
                discountInput.value = 0;
                depositSection.classList.add('d-none');
                return;
            }

            fetchCalendarData(packageId);
        });
        function fetchCalendarData(packageId) {
            fetch(`/organizer/business/packages/${packageId}/calendar-data`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('calendarSection').classList.remove('d-none');
                    let currentDate = new Date();
                    renderCalendar(currentDate, data, packageId);
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to load calendar');
                });
        }

        // 2️⃣ When changing discount
        discountInput.addEventListener('input', updateFinalPrice);

        // 3️⃣ When changing payment method
        paymentMethodSelect.addEventListener('change', function () {
            if (this.value === 'deposit') {
                depositSection.classList.remove('d-none');
                updateRemainingBalance();
            } else {
                depositSection.classList.add('d-none');
                remainingBalanceInput.value = '';
                customerPayInput.value = '';
            }
        });

        // 4️⃣ When customer enters deposit amount
        customerPayInput.addEventListener('input', updateRemainingBalance);

        // Functions
        function updateFinalPrice() {
            const quantity = selectedTimes.length; // number of selected time slots

            console.log(quantity);
            // If no time slot selected, package price = 0
            const basePrice = quantity > 0 ? parseFloat(packagePriceInput.value) : 0;
            const discount = parseFloat(discountInput.value) || 0;

            // Calculate final price
            const finalPrice = Math.max((basePrice * quantity) - discount, 0);
            packagePriceInput.value = basePrice; // show base price
            finalPriceInput.value = finalPrice;

            updateRemainingBalance();
        }


        function updateRemainingBalance() {
            const finalPrice = parseFloat(finalPriceInput.value) || 0;
            const customerPay = parseFloat(customerPayInput.value) || 0;
            const remaining = Math.max(finalPrice - customerPay, 0);
            remainingBalanceInput.value = remaining;
        }

    </script>

@endpush