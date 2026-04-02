@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-3">

    <h4 class="mb-4 fw-bold">@section('page-title')
⚙️ Settings
@endsection</h4>

    <div class="card shadow-sm border-0 p-4">

        <h5 class="mb-3">Appearance</h5>

        <!-- DARK / LIGHT MODE -->
        <div class="form-group">
            <label class="fw-bold">Theme Mode</label>
            <select id="themeMode" class="form-control">
                <option value="light">☀️ Light Mode</option>
                <option value="dark">🌙 Dark Mode</option>
            </select>
        </div>

        <small class="text-muted">
            Choose your preferred dashboard appearance.
        </small>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const themeSelect = document.getElementById('themeMode');

    // load saved theme
    let savedTheme = localStorage.getItem('theme') || 'light';
    themeSelect.value = savedTheme;

    applyTheme(savedTheme);

    themeSelect.addEventListener('change', function () {
        let selected = this.value;

        localStorage.setItem('theme', selected);
        applyTheme(selected);
    });

    function applyTheme(mode){
        if(mode === 'dark'){
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

});
</script>
@endpush