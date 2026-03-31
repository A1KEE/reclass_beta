{{-- ========================================================= --}}
{{-- III. SUMMARY OF THE ACHIEVEMENT OF PPST INDICATORS --}}
{{-- ADMIN VERSION (READ-ONLY WITH SAVED DATA) --}}
{{-- ========================================================= --}}

<hr class="mt-4 mb-4">

<h5 class="fw-bold text-uppercase" id="ppst-summary">
Summary of the Achievement of PPST Indicators
</h5>

<p class="text-muted mb-3 fst-italic">
*Put a (/) mark if the applicant meets the required PPST indicators;
if not, put an (X) mark in the "O", "VS", and "S" columns.
</p>

@php
$domains = [
1 => 'Content Knowledge and Pedagogy',
2 => 'Learning Environment',
3 => 'Diversity of Learners',
4 => 'Curriculum and Planning',
5 => 'Assessment and Reporting',
6 => 'Community Linkages and Professional Engagement',
7 => 'Personal Growth and Professional Development'
];

$currentDomain = null;
@endphp

<div class="table-responsive mb-4">
<table class="table table-bordered align-middle">
<thead class="text-center">
<tr>
<th style="width:8%">No.</th>
<th>Domain / Strand / Indicators</th>
<th style="width:10%">O</th>
<th style="width:10%">VS</th>
<th style="width:10%">S</th>
</tr>
</thead>
<tbody>

@foreach($ppstIndicators as $indicator)

@php
    $rating = $ratings[$indicator->id]->rating ?? null;
@endphp

@if($currentDomain != $indicator->domain)
<tr class="table-secondary fw-bold">
<td></td>
<td colspan="4">
Domain {{ $indicator->domain }}.
{{ $domains[$indicator->domain] ?? '' }}
</td>
</tr>
@php $currentDomain = $indicator->domain; @endphp
@endif

<tr class="{{ $indicator->indicator_type == 'COI' ? 'row-coi' : 'row-ncoi' }}">
<td class="text-center fw-semibold">{{ $loop->iteration }}</td>
<td>{{ $indicator->indicator_text }}</td>

{{-- ================= O ================= --}}
<td class="text-center">
<input type="checkbox"
name="ppst[{{ $indicator->id }}][O]"
value="1"
{{ $rating === 'O' ? 'checked' : '' }}
disabled
class="ppst-checkbox"
data-type="{{ $indicator->indicator_type }}"
data-id="{{ $indicator->id }}"
data-column="O">
</td>

{{-- ================= VS ================= --}}
<td class="text-center">
<input type="checkbox"
name="ppst[{{ $indicator->id }}][VS]"
value="1"
{{ $rating === 'VS' ? 'checked' : '' }}
disabled
class="ppst-checkbox"
data-type="{{ $indicator->indicator_type }}"
data-id="{{ $indicator->id }}"
data-column="VS">
</td>

{{-- ================= S ================= --}}
<td class="text-center">
<input type="checkbox"
name="ppst[{{ $indicator->id }}][S]"
value="1"
{{ $rating === 'S' ? 'checked' : '' }}
disabled
class="ppst-checkbox-s"
data-type="{{ $indicator->indicator_type }}"
data-id="{{ $indicator->id }}"
data-column="S">
</td>

</tr>
@endforeach

{{-- ================= TOTALS ================= --}}
<tr class="fw-bold table-light text-center">
    <td></td>
    <td class="text-center">Total Number of COI with Outstanding</td>
    <td></td>
    <td>
        <input type="number"
               id="totalCOI_O"
               readonly
               class="form-control form-control-sm text-center"
               style="width:80px;margin:auto;">
    </td>
    <td></td>
</tr>

<tr class="fw-bold table-light text-center">
    <td></td>
    <td class="text-center">Total Number of COI with Very Satisfactory</td>
    <td></td>
    <td>
        <input type="number"
               id="totalCOI_VS"
               readonly
               class="form-control form-control-sm text-center"
               style="width:80px;margin:auto;">
    </td>
    <td></td>
</tr>

<tr class="fw-bold table-light text-center">
    <td></td>
    <td class="text-center">Total Number of NCOI with Outstanding</td>
    <td></td>
    <td>
        <input type="number"
               id="totalNCOI_O"
               readonly
               class="form-control form-control-sm text-center"
               style="width:80px;margin:auto;">
    </td>
    <td></td>
</tr>

<tr class="fw-bold table-light text-center">
    <td></td>
    <td class="text-center">Total Number of NCOI with Very Satisfactory</td>
    <td></td>
    <td>
        <input type="number"
               id="totalNCOI_VS"
               readonly
               class="form-control form-control-sm text-center"
               style="width:80px;margin:auto;">
    </td>
    <td></td>
</tr>

{{-- ================= FINAL RESULT ================= --}}
<tr class="fw-bold table-secondary">
    <td colspan="5">

        <div class="p-2 rounded bg-light border">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <!-- LEFT -->
                <div>
                    Final Result: 
                    <span id="finalRating" class="fw-bold">-</span>
                </div>

                <!-- RIGHT -->
                <div class="text-end">
                    <div id="ncoiWarning" style="color:red; font-size:13px;"></div>
                    <div id="ppstProgress" style="color:#555; font-size:13px;"></div>
                </div>

            </div>

        </div>

    </td>
</tr>

</tbody>
</table>
</div>