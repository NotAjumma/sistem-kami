@extends('layouts.admin.default')
@push('styles')
    <style>
        .btn-verify-payment {
            gap: 5px;
            width: auto !important;
            padding: 0 10px !important;
        }
    </style>
@endpush
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('content')
    <div class="container-fluid" style="height: 100vh;">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $page_title ?? '' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form method="POST" action="{{ route('worker.fishing.key_in_weight') }}" class="">
                                @csrf

                                <div class="mb-3">
                                    <label for="event_id" class="form-label">Event:</label>
                                    <select name="event_id" id="event_id" class="form-select select2" required>
                                        @foreach ($events as $index => $event)
                                            <option value="{{ $event->id }}" {{ $index === 0 ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                {{-- Section shown if event_id == 1 --}}
                                <div id="manual-input-fields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="participant_user_id" class="form-label">Participant User ID:</label>
                                        <input type="text" name="participant_manual_id" id="participant_user_id"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="participant_name" class="form-label">Participant Name:</label>
                                        <input type="text" name="participant_name" id="participant_name"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="caught_time" class="form-label">Time Caught:</label>
                                        <input type="time" name="caught_time" id="caught_time" class="form-control">
                                    </div>
                                </div>

                                {{-- Section shown if event_id != 1 --}}
                                <div id="participant-select-field">
                                    <div class="mb-3">
                                        <label for="participant_id" class="form-label">Participant:</label>
                                        <select name="participant_id" id="participant_id_select" class="form-select select2"
                                            required data-placeholder="Search Name">
                                            <option value="">-- Choose Participant --</option>
                                            @foreach ($participants as $participant)
                                                <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg):</label>
                                    <input type="number" step="0.01" min="0.1" name="weight" id="weight"
                                        class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection

    @push('scripts')
        <!-- Include jQuery & Select2 JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Initialize Select2 -->
        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    width: '100%',
                    placeholder: function () {
                        return $(this).data('placeholder'); // Use data-placeholder from the element
                    },
                    allowClear: true
                });
            });
        </script>
        <script>
            function toggleParticipantFields() {
                const selectedEventId = document.getElementById('event_id').value;
                const manualSection = document.getElementById('manual-input-fields');
                const selectSection = document.getElementById('participant-select-field');
                const participantSelect = document.getElementById('participant_id_select');

                if (parseInt(selectedEventId) === 2) {
                    manualSection.style.display = 'block';
                    selectSection.style.display = 'none';
                    participantSelect.removeAttribute('required'); // remove required when hidden
                } else {
                    manualSection.style.display = 'none';
                    selectSection.style.display = 'block';
                    participantSelect.setAttribute('required', 'required'); // add required when shown
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                toggleParticipantFields(); // initialize on load
                document.getElementById('event_id').addEventListener('change', toggleParticipantFields);
            });
        </script>
    @endpush