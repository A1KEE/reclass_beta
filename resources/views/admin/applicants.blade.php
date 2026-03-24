@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h4 class="mb-3 fw-bold">👥 Applicants</h4>

    <div class="card shadow-sm border-0 p-3">

        <div class="table-responsive">
            <table id="applicantsTable" class="table table-bordered table-striped table-sm">

               <thead class="thead-light">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Current Position</th>
        <th>Position Applied</th>
        <th>Item Number</th>
        <th>School</th>
        <th>Salary</th>
        <th>Levels</th>
        <th>Status</th>
        <th>Created</th>
        <th width="120">Action</th>
    </tr>
</thead>

                <tbody>
@foreach($applicants as $a)
<tr>
    <td>{{ $a->id }}</td>
    <td>{{ $a->name }}</td>
    <td>{{ $a->current_position }}</td>
    <td>{{ $a->position_applied }}</td>
    <td>{{ $a->item_number }}</td>
    <td>{{ $a->school_name }}</td>
    <td>{{ $a->sg_annual_salary }}</td>

    <!-- LEVELS (JSON) -->
    <td>
        {{ is_string($a->levels) ? implode(', ', json_decode($a->levels, true) ?? []) : '' }}
    </td>

    <!-- STATUS BADGE -->
    <td>
        @if($a->status == 'submitted')
            <span class="badge badge-success">Submitted</span>
        @elseif($a->status == 'draft')
            <span class="badge badge-secondary">Draft</span>
        @else
            <span class="badge badge-dark">{{ $a->status }}</span>
        @endif
    </td>

    <td>{{ $a->created_at }}</td>

    <td>
        <a href="{{ route('admin.applicants.show', $a->id) }}"
           class="btn btn-sm btn-primary">
            View
        </a>
    </td>
</tr>
@endforeach
</tbody>

            </table>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
let table;

$(document).ready(function () {

    table = $('#applicantsTable').DataTable({
        responsive: true,
        autoWidth: false,
        scrollX: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ordering: true,
        language: {
            search: "🔍 Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ applicants",
            paginate: {
                previous: "Prev",
                next: "Next"
            }
        }
    });

});
</script>
@endpush