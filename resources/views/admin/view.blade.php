@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <!-- <h3>👁️ Applicant Full Details</h3> -->

    {{-- reuse form --}}
    @include('applicants.form-view', ['application' => $application])

    <a href="{{ route('admin.applicants') }}" class="btn btn-secondary mt-3">⬅ Back</a>

</div>

@endsection