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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Fishing Leaderboard</h4>

                        <form method="GET" action="{{ route('worker.fishing.leaderboard') }}">
                            <div class="input-group">
                                <select name="leaderboard_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Choose Leaderboard --</option>
                                    @foreach ($allLeaderboards as $lb)
                                        <option value="{{ $lb->id }}" {{ $leaderboard && $leaderboard->id == $lb->id ? 'selected' : '' }}>
                                            {{ $lb->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    @if ($leaderboard)
                        <div class="card-body">
                            <h5 class="mb-3">{{ $leaderboard->name }} - Results</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Participant</th>
                                            <th>Phone</th>
                                            <th>Weight (kg)</th>
                                            @if ($leaderboard->rank->type === 'closest_to_target')
                                                <th>Difference</th>
                                            @endif
                                            <th>Catch At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $result)
                                            <tr>
                                                <td>
                                                    @if ($result->rank == 1)
                                                        🥇
                                                    @elseif ($result->rank == 2)
                                                        🥈
                                                    @elseif ($result->rank == 3)
                                                        🥉
                                                    @else
                                                        {{ $result->rank }}
                                                    @endif
                                                </td>
                                                <td>{{ $result->participant->name ?? 'Unknown' }}</td>
                                                <td>{{ $result->participant->phone ?? 'Unknown' }}</td>
                                                <td>{{ number_format($result->total_weight, 2) }}</td>
                                                @if ($leaderboard->rank->type === 'closest_to_target')
                                                    <td>{{ number_format($result->difference, 2) }}</td>
                                                @endif
                                               <td>
                                                    {{ $result->caught_at ? \Carbon\Carbon::parse($result->caught_at)->format('h:i:s A') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
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
    @endpush