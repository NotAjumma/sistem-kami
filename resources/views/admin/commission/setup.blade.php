@extends('layouts.admin.default')

@section('content')
<div class="container-fluid">
    <h3>Commission Setup</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('commission.setup.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Worker</label>
                            <select name="worker_id" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($workers as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Package (optional)</label>
                            <select name="package_id" id="packageSelect" class="form-control">
                                <option value="">Select Package</option>
                                @foreach($packages as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Addon (optional)</label>
                            <select name="addon_id" id="addonSelect" class="form-control">
                                <option value="">None</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="commission_type" class="form-control" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed (RM)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" step="0.01" min="0" name="commission_value" class="form-control" required />
                        </div>

                        <button class="btn btn-primary">Save Rule</button>
                    </form>
                </div>
            </div>

            
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Existing Rules</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Worker</th>
                                <th>Package</th>
                                <th>Addon</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rules as $r)
                                <tr>
                                    <td>{{ $r->id }}</td>
                                    <td>{{ $r->worker->name ?? $r->worker_id }}</td>
                                    <td>{{ $r->package->name ?? '-' }}</td>
                                    <td>{{ $r->addon->name ?? '-' }}</td>
                                    <td>{{ $r->commission_type }}</td>
                                    <td>{{ $r->commission_value }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('commission.rule.delete', $r) }}" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
document.getElementById('packageSelect').addEventListener('change', function () {
    let packageId = this.value;
    let addonSelect = document.getElementById('addonSelect');

    addonSelect.innerHTML = '<option value="">Loading...</option>';

    if (!packageId) {
        addonSelect.innerHTML = '<option value="">None</option>';
        return;
    }

    fetch(`/organizer/business/package/${packageId}/addons`)
        .then(res => res.json())
        .then(data => {

            addonSelect.innerHTML = '<option value="">None</option>';

            data.forEach(addon => {
                addonSelect.innerHTML += 
                    `<option value="${addon.id}">${addon.name}</option>`;
            });

        });
});
</script>
@endpush