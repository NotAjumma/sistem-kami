@extends('home.homeLayout')
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

        /* .calendar .selected {
                                                        background-color: #000;
                                                        color: #fff;
                                                    } */

        .today {
            background-color: #ffc107 !important;
            color: #000;
        }

        .selected {
            background-color: #0d6efd !important;
            color: #fff;
        }

        .off-day {
            background-color: rgb(238, 7, 7) !important;
            color: rgb(248, 248, 248) !important;
            cursor: not-allowed;
        }

        .booked-day {
            background-color: #ffeeba !important;
            color: #856404 !important;
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
    </style>
@endpush

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Calendar Navigation Controls -->
                <div class="calendar-navigation">
                    <button id="prevMonth" class="btn btn-secondary">Previous</button>
                    <h3 id="currentMonth">January 2024</h3>
                    <button id="todayBtn" class="btn btn-outline-primary">Today</button>
                    <button id="nextMonth" class="btn btn-secondary">Next</button>
                </div>
                <table class="calendar table table-bordered">
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
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td class="selected">4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td class="highlight">11</td>
                            <td>12</td>
                            <td>13</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>16</td>
                            <td>17</td>
                            <td class="selected">18</td>
                            <td>19</td>
                            <td>20</td>
                            <td>21</td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>23</td>
                            <td>24</td>
                            <td>25</td>
                            <td>26</td>
                            <td>27</td>
                            <td>28</td>
                        </tr>
                        <tr>
                            <td>29</td>
                            <td>30</td>
                            <td>31</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- <div class="col-md-6">
                <h3>Thursday</h3>
                <div class="btn-group mb-3" role="group">
                    <button type="button" class="btn btn-secondary">12h</button>
                    <button type="button" class="btn btn-secondary">24h</button>
                </div>
                <div class="time-slots">
                    <div class="time-slot">9:00 am</div>
                    <div class="time-slot">9:15 am</div>
                    <div class="time-slot">9:30 am</div>
                    <div class="time-slot">9:45 am</div>
                    <div class="time-slot selected">10:00 am</div>
                    <div class="time-slot">10:15 am</div>
                    <div class="time-slot">10:30 am</div>
                    <div class="time-slot">10:45 am</div>
                </div>
            </div> -->
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const vendorTimeSlots = @json($timeSlots);
        const vendorOffDays = @json($offDays);
        const bookedVendorDates = @json($bookedDates);
        const limitReachedDays = @json($limitReachedDays);
        const bookedDatesFormatted = @json($bookedDatesFormatted);
        const weekRangeBlock = @json($weekRangeBlock);
        const calendarBody = document.getElementById("calendarBody");
        const currentMonthDisplay = document.getElementById("currentMonth");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");

        console.log(vendorOffDays);
        let currentDate = new Date();

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-indexed (0 = January)
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const firstWeekday = (firstDay.getDay() + 6) % 7; // Make Monday = 0
            const totalDays = lastDay.getDate();
            const offDaysFormatted = vendorOffDays.map(off => off.off_date);
            const bookedDatesFormatted = bookedVendorDates.map(date => String(date));

            // Update header
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            currentMonthDisplay.textContent = `${monthNames[month]} ${year}`;

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
                } else if (bookedDatesFormatted.includes(formattedDate)) {
                    td.classList.add("booked-day");
                    td.title = "Unavailable (Fully Booked)";
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

                td.addEventListener("click", () => {
                    document.querySelectorAll("#calendarBody td").forEach(cell => cell.classList.remove("selected"));
                    td.classList.add("selected");

                    // Log selected date in yyyy-mm-dd format
                    const selectedDate = new Date(year, month, day);
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    console.log(`${yyyy}-${mm}-${dd}`);
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
    </script>

@endpush