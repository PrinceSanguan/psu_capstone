@extends('layouts.client')

@section('title', 'Assessment Schedules')

@section('styles')
<style>
    .calendar-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        background-color: #4e73df;
        color: #fff;
        padding: 15px;
        text-align: center;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .calendar-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .calendar-navigation {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .calendar-nav-btn {
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .calendar-nav-btn:hover {
        background-color: rgba(255, 255, 255, 0.4);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day {
        padding: 10px;
        min-height: 100px;
        border: 1px solid #e3e6f0;
        position: relative;
    }

    .calendar-day:hover {
        background-color: #f8f9fc;
    }

    .calendar-day.today {
        background-color: #f1f8ff;
    }

    .calendar-day.other-month {
        background-color: #f9f9f9;
        color: #999;
    }

    .day-header {
        font-weight: bold;
        text-align: center;
        padding: 5px;
        background-color: #f8f9fc;
    }

    .day-number {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 0.8rem;
        color: #858796;
    }

    .day-number.today {
        background-color: #4e73df;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .calendar-event {
        background-color: #4e73df;
        color: white;
        padding: 3px 5px;
        border-radius: 3px;
        margin-bottom: 5px;
        font-size: 0.8rem;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .calendar-event.quiz {
        background-color: #1cc88a;
    }

    .calendar-event.unit_test {
        background-color: #f6c23e;
    }

    .calendar-event.activity {
        background-color: #36b9cc;
    }

    .calendar-event.midterm_exam, .calendar-event.final_exam {
        background-color: #e74a3b;
    }

    .calendar-selector {
        margin-bottom: 20px;
    }

    #event-detail-container {
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Assessment Schedules</h1>

    <!-- List View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upcoming Assessments</h6>
        </div>
        <div class="card-body">
            @if($upcomingAssessments->isEmpty())
                <p class="text-center">No assessments scheduled at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Term</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAssessments as $assessment)
                                <tr>
                                    <td>{{ $assessment->subject_code }} - {{ $assessment->subject_name }}</td>
                                    <td>{{ $assessment->title }}</td>
                                    <td>
                                        <span class="badge
                                            @if($assessment->type == 'quiz') badge-success
                                            @elseif($assessment->type == 'unit_test') badge-warning
                                            @elseif($assessment->type == 'activity') badge-info
                                            @else badge-danger
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $assessment->type)) }}
                                        </span>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($assessment->schedule_date)) }}</td>
                                    <td>
                                        @if($assessment->schedule_time)
                                            {{ date('h:i A', strtotime($assessment->schedule_time)) }}
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($assessment->term) }}</td>
                                    <td>{{ $assessment->faculty_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Calendar View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Calendar View</h6>
        </div>
        <div class="card-body">
            <!-- Month/Year Selector -->
            <div class="calendar-selector">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select id="month-selector" class="form-control">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <select id="year-selector" class="form-control">
                                @for($year = date('Y') - 1; $year <= date('Y') + 2; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                            <div class="input-group-append">
                                <button id="go-to-date" class="btn btn-primary">Go</button>
                                <button id="go-to-today" class="btn btn-secondary">Today</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="calendar-container">
                <div class="calendar-header">
                    <button class="calendar-nav-btn" id="prev-month">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 id="current-month-year"></h2>
                    <button class="calendar-nav-btn" id="next-month">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="calendar-grid">
                    <!-- Day headers and calendar cells will be dynamically generated -->
                </div>
            </div>

            <!-- Event details display area -->
            <div id="event-detail-container"></div>

            <!-- Calendar Legend -->
            <div class="mt-4">
                <h6 class="font-weight-bold">Legend:</h6>
                <div class="d-flex flex-wrap">
                    <div class="mr-3 mb-2">
                        <span class="badge badge-success">Quiz</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-warning">Unit Test</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-info">Activity</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-danger">Exam (Midterm/Final)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Assessment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="eventDetailModalBody">
                <!-- Event details will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[3, "asc"]]  // Sort by date by default
        });

        // Load all assessments data for client-side filtering
        const assessmentsData = @json($upcomingAssessments);

        // Current date for highlighting today
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Initialize with current month and year
        let currentMonth = today.getMonth() + 1; // 1-12
        let currentYear = today.getFullYear();

        // Set initial values for selectors
        $('#month-selector').val(currentMonth);
        $('#year-selector').val(currentYear);

        // Render calendar initially
        renderCalendar(currentMonth, currentYear);

        // Previous month button
        $('#prev-month').click(function() {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
            $('#month-selector').val(currentMonth);
            $('#year-selector').val(currentYear);
        });

        // Next month button
        $('#next-month').click(function() {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
            $('#month-selector').val(currentMonth);
            $('#year-selector').val(currentYear);
        });

        // Go to specified month/year
        $('#go-to-date').click(function() {
            currentMonth = parseInt($('#month-selector').val());
            currentYear = parseInt($('#year-selector').val());
            renderCalendar(currentMonth, currentYear);
        });

        // Go to today button
        $('#go-to-today').click(function() {
            const today = new Date();
            currentMonth = today.getMonth() + 1;
            currentYear = today.getFullYear();
            $('#month-selector').val(currentMonth);
            $('#year-selector').val(currentYear);
            renderCalendar(currentMonth, currentYear);
        });

        // Function to render the calendar with fixed date numbering
        function renderCalendar(month, year) {
            // Update header text
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $('#current-month-year').text(monthNames[month - 1] + ' ' + year);

            // Get first day of month and number of days
            const firstDay = new Date(year, month - 1, 1).getDay(); // 0-6 (0 = Sunday)
            const daysInMonth = new Date(year, month, 0).getDate(); // 28-31

            // Get days in previous month
            const prevMonth = month - 1 > 0 ? month - 1 : 12;
            const prevYear = prevMonth === 12 ? year - 1 : year;
            const daysInPrevMonth = new Date(prevYear, prevMonth, 0).getDate();

            // Clear previous calendar grid
            $('.calendar-grid').empty();

            // Add day headers
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                $('.calendar-grid').append($('<div>').addClass('day-header').text(day));
            });

            // Calculate days for current month display
            let dayCount = 1;
            let nextMonthDay = 1;

            // Determine number of rows needed (either 5 or 6 weeks)
            const totalDays = firstDay + daysInMonth;
            const rows = Math.ceil(totalDays / 7);

            // Create calendar rows
            for (let i = 0; i < rows; i++) {
                // Create week row (7 days)
                for (let j = 0; j < 7; j++) {
                    // Calculate the day position (0-41)
                    const pos = i * 7 + j;

                    // Create day element
                    let dayElement = $('<div>').addClass('calendar-day');
                    let dayNumberElement = $('<div>').addClass('day-number');
                    let dayDate;

                    // Fill days from previous month
                    if (pos < firstDay) {
                        const prevMonthDate = daysInPrevMonth - (firstDay - pos - 1);
                        dayNumberElement.text(prevMonthDate);
                        dayElement.addClass('other-month');

                        // Create date object for previous month
                        dayDate = new Date(prevYear, prevMonth - 1, prevMonthDate);
                    }
                    // Fill days from current month
                    else if (pos < firstDay + daysInMonth) {
                        dayNumberElement.text(dayCount);

                        // Check if this is today
                        const thisDate = new Date(year, month - 1, dayCount);
                        if (thisDate.toDateString() === today.toDateString()) {
                            dayElement.addClass('today');
                            dayNumberElement.addClass('today');
                        }

                        dayDate = thisDate;
                        dayCount++;
                    }
                    // Fill days from next month
                    else {
                        dayNumberElement.text(nextMonthDay);
                        dayElement.addClass('other-month');

                        // Calculate next month and year
                        const nextMonth = month + 1 > 12 ? 1 : month + 1;
                        const nextYear = nextMonth === 1 ? year + 1 : year;

                        dayDate = new Date(nextYear, nextMonth - 1, nextMonthDay);
                        nextMonthDay++;
                    }

                    dayElement.append(dayNumberElement);

                    // Find events for this day
                    if (dayDate) {
                        const dateString = dayDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD

                        const dayEvents = assessmentsData.filter(assessment => {
                            if (!assessment.schedule_date) return false;
                            const assessmentDate = new Date(assessment.schedule_date);
                            return assessmentDate.toISOString().split('T')[0] === dateString;
                        });

                        // Add events to the day
                        dayEvents.forEach(event => {
                            const eventElement = $('<div>')
                                .addClass('calendar-event ' + event.type)
                                .text(event.title)
                                .attr('data-event-id', event.id)
                                .attr('title', event.subject_code + ' - ' + event.title);

                            // Add click event to show details
                            eventElement.click(function() {
                                showEventDetails(event);
                            });

                            dayElement.append(eventElement);
                        });
                    }

                    // Add day to grid
                    $('.calendar-grid').append(dayElement);
                }
            }
        }

        // Function to show event details in modal
        function showEventDetails(event) {
            let typeClass = 'primary';
            if (event.type === 'quiz') typeClass = 'success';
            else if (event.type === 'unit_test') typeClass = 'warning';
            else if (event.type === 'activity') typeClass = 'info';
            else if (event.type === 'midterm_exam' || event.type === 'final_exam') typeClass = 'danger';

            const eventTime = event.schedule_time
                ? new Date('1970-01-01T' + event.schedule_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                : 'Not specified';

            const modalContent = `
                <div class="card border-left-${typeClass} shadow mb-3">
                    <div class="card-body">
                        <h5 class="card-title">${event.title}</h5>
                        <p class="mb-1"><strong>Subject:</strong> ${event.subject_code} - ${event.subject_name}</p>
                        <p class="mb-1"><strong>Type:</strong> ${event.type.replace('_', ' ').toUpperCase()}</p>
                        <p class="mb-1"><strong>Date:</strong> ${new Date(event.schedule_date).toLocaleDateString()}</p>
                        <p class="mb-1"><strong>Time:</strong> ${eventTime}</p>
                        <p class="mb-1"><strong>Term:</strong> ${event.term.toUpperCase()}</p>
                        <p class="mb-1"><strong>Teacher:</strong> ${event.faculty_name}</p>
                        ${event.max_score ? `<p class="mb-1"><strong>Maximum Score:</strong> ${event.max_score}</p>` : ''}
                    </div>
                </div>
            `;

            $('#eventDetailModalBody').html(modalContent);
            $('#eventDetailModal').modal('show');
        }
    });
</script>
@endsection
