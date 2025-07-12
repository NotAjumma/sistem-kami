<div class="row">
    @foreach ($leaderboardResults as $set)
        @php
            $leaderboard = $set['leaderboard'];
            $results = $set['results'];
        @endphp

        <div class="col-12 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $leaderboard->name }} - Results</h5>
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
