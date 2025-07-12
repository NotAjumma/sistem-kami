@extends('home.homeLayout')
@push('styles')
    <style>
        .event-title {
            font-weight: 900;
            font-size: 1.5rem;
            color: black;
            margin-bottom: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" style="background: rgba(0, 31, 77, 1); padding-bottom: 150px;">
        <div class="container" style="margin-top: 0px;">
            <div class="d-flex justify-content-center py-5">
                <div style="background: rgba(255, 255, 255, 1); padding: 25px; text-align: center; border-radius: 10px;">
                    <h2 class="event-title m-0">{{ $event->title }} - Leaderboard</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($leaderboardResults as $set)
                @php
                    $leaderboard = $set['leaderboard'];
                    $results = $set['results'];
                @endphp

                <div class="col-12 col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 style="font-weight: bold; font-size: large" class="mb-0">{{ $leaderboard->name }} - Results</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle text-center">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Participant</th>
                                            @if ($leaderboard->event_id == 1)
                                                <th>User ID</th>
                                            @else
                                                <th>Phone</th>
                                            @endif
                                            <th>Weight (kg)</th>
                                            @if ($leaderboard->rank->type === 'closest_to_target')
                                                <th>Difference</th>
                                            @endif
                                            <th>Catch At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results as $result)
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
                                                <td>
                                                    @if ($leaderboard->event_id == 1)
                                                        {{ $result->participant->no_ic ?? 'Unknown' }}
                                                    @else
                                                        {{ $result->participant->phone ?? 'Unknown' }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($result->total_weight, 2) }}</td>
                                                @if ($leaderboard->rank->type === 'closest_to_target')
                                                    <td>{{ number_format($result->difference, 2) }}</td>
                                                @endif
                                                <td>{{ $result->caught_at ? \Carbon\Carbon::parse($result->caught_at)->format('h:i A') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">No results yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')

@endpush