@extends('layouts.app')

@section('title', 'Master Data - Sistem Qurban')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-dark">Master Data</h3>
        <p class="text-muted mb-0">Kelola data referensi kandang, jenis ternak, dan kriteria qurban.</p>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
        <ul class="nav nav-tabs" id="masterDataTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-semibold" id="tipe-tab" data-bs-toggle="tab"
                    data-bs-target="#tipe-pane" type="button" role="tab">Tipe Ternak</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="ras-tab" data-bs-toggle="tab"
                    data-bs-target="#ras-pane" type="button" role="tab">Ras Ternak</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="kandang-tab" data-bs-toggle="tab"
                    data-bs-target="#kandang-pane" type="button" role="tab">Kandang</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-semibold" id="kriteria-tab" data-bs-toggle="tab"
                    data-bs-target="#kriteria-pane" type="button" role="tab">Kriteria Qurban</button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="masterDataTabContent">

            @include('dashboards.master.components.tab-tipe')
            @include('dashboards.master.components.tab-ras')
            @include('dashboards.master.components.tab-kandang')
            @include('dashboards.master.components.tab-kriteria')

        </div>
    </div>
</div>

@push('scripts')
<script>
window.initMasterTooltips = function (context = document) {
    let triggers = context.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...triggers].forEach(el => {
        let instance = bootstrap.Tooltip.getInstance(el);
        if (instance) instance.dispose();
        new bootstrap.Tooltip(el);
    });
};

window.initTablePagination = function (tableBodyId, paginationId, itemsPerPage = 5) {
    const tbody = document.getElementById(tableBodyId);
    const paginationContainer = document.getElementById(paginationId);
    if (!tbody || !paginationContainer) return null;

    let currentPage = 1;

    function render() {
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => {
            return !tr.querySelector('td[colspan]');
        });

        const totalItems = rows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        rows.forEach((row, index) => {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            if (index >= start && index < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

            const firstTd = row.querySelector('td:first-child');
            if (firstTd && !isNaN(parseInt(firstTd.innerText))) {
                firstTd.innerText = index + 1;
            }
        });

        paginationContainer.innerHTML = '';
        
        // Re-initialize tooltips on the visible rows
        window.initMasterTooltips(tbody);

        if (totalPages <= 1) {
            return;
        }

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0 gap-1';

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        const prevA = document.createElement('a');
        prevA.className = 'page-link rounded-2';
        prevA.href = '#';
        prevA.innerHTML = '&laquo;';
        prevA.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                render();
            }
        });
        prevLi.appendChild(prevA);
        ul.appendChild(prevLi);

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${currentPage === i ? 'active' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link rounded-2';
            a.href = '#';
            a.innerText = i;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                render();
            });
            li.appendChild(a);
            ul.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        const nextA = document.createElement('a');
        nextA.className = 'page-link rounded-2';
        nextA.href = '#';
        nextA.innerHTML = '&raquo;';
        nextA.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                render();
            }
        });
        nextLi.appendChild(nextA);
        ul.appendChild(nextLi);

        nav.appendChild(ul);
        paginationContainer.appendChild(nav);
    }

    render();

    return {
        update: function () {
            render();
        },
        setCurrentPage: function (page) {
            currentPage = page;
            render();
        }
    };
};

document.addEventListener("DOMContentLoaded", function () {
    window.initMasterTooltips();
});
</script>
@endpush
@endsection
