@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-wrapper {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin: auto;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.06);
        }

        .dashboard-card {
            border-radius: 1rem;
            background-color: #f4f0fa;
            padding: 1.25rem;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .dashboard-card h6 {
            font-weight: 600;
            font-size: 11px;
            color: rgb(152, 101, 182);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }

        .dashboard-card .value {
            font-size: 20px;
            font-weight: bold;
            color: rgb(62, 9, 75);
        }

        .chart-container {
            background-color: #fff;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            height: 100%;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .chart-container h6 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #2e1a51;
        }

        canvas {
            max-height: 180px !important;
        }

        #kreditMacetItems {
            list-style: none;
            padding-left: 0;
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 0;
            font-size: 13px;
            color: #444;
        }

        #kreditMacetItems li {
            margin-bottom: 6px;
        }

        @media (max-width: 768px) {

            .col-md-3,
            .col-md-4,
            .col-md-6,
            .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>

    <div class="container-fluid py-3">
        <div class="dashboard-wrapper">
            <!-- Info Card -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 g-3 mb-4">
                <div class="col text-center">
                    <div class="dashboard-card">
                        <h6>Jumlah Anggota</h6>
                        <div class="value" id="jumlahAnggota">{{ $activeMembersCount ?? 0 }}</div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="dashboard-card">
                        <h6>Jumlah Simpanan</h6>
                        <div class="value" id="jumlahSimpanan">{{ number_format($totalSimpanan ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="dashboard-card">
                        <h6>Jumlah Angsuran</h6>
                        <div class="value" id="jumlahAngsuran">{{ number_format($totalAngsuran ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="dashboard-card">
                        <h6>Jumlah Pinjaman</h6>
                        <div class="value" id="jumlahPinjaman">{{ number_format($totalPinjaman ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="dashboard-card">
                        <h6>Pendapatan Bunga</h6>
                        <div class="value" id="totalBunga">0</div>
                    </div>
                </div>
            </div>

            <!-- Line Chart & Kredit Macet -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="chart-container h-100">
                        <h6>Perkembangan Data Bulanan</h6>
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container h-100" id="kreditMacetList">
                        <h6>Daftar Kredit Macet</h6>
                        <ul id="kreditMacetItems">
                            <li style="color: #999;">Memuat data...</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bar Chart & Donut Chart -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="chart-container h-100">
                        <h6>Laporan Jumlah Kas Masuk</h6>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container h-100">
                        <h6>Total Komposisi</h6>
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ambil data chart dari API
            fetch('/dashboard/chart-data')
                .then(res => res.json())
                .then(data => {
                    const months = data.labels;
                    const simpananData = data.simpanan.map(item => item.total);
                    const pinjamanData = data.pinjaman.map(item => item.total);
                    const anggotaData = data.anggota.map(item => item.total);

                    // LINE CHART
                    const ctxLine = document.getElementById('lineChart').getContext('2d');
                    new Chart(ctxLine, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [
                                {
                                    label: 'Simpanan',
                                    data: simpananData,
                                    borderColor: '#7e57c2',
                                    backgroundColor: 'rgba(126, 87, 194, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#7e57c2'
                                },
                                {
                                    label: 'Pinjaman',
                                    data: pinjamanData,
                                    borderColor: '#000000',
                                    backgroundColor: 'rgba(0, 0, 0, 0.05)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#000000'
                                },
                                {
                                    label: 'Anggota',
                                    data: anggotaData,
                                    borderColor: '#9e9e9e',
                                    backgroundColor: 'rgba(158, 158, 158, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#9e9e9e'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top', labels: { color: '#333' } },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: ctx => {
                                            const label = ctx.dataset.label;
                                            const value = ctx.parsed.y;
                                            if (label === 'Anggota') {
                                                return `${label}: ${value.toLocaleString('id-ID')}`;
                                            }
                                            return `${label}: Rp. ${value.toLocaleString('id-ID')}`;
                                        }
                                    }
                                }
                            },
                            interaction: { mode: 'nearest', axis: 'x', intersect: false },
                            scales: {
                                x: {
                                    ticks: { color: '#333' },
                                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#333',
                                        callback: function (val) {
                                            // Jika nilai kecil, anggap itu jumlah orang
                                            if (val <= 1000) {
                                                return val.toLocaleString('id-ID');
                                            }
                                            return 'Rp. ' + val.toLocaleString('id-ID');
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                }
                            }
                        }
                    });

                    // BAR CHART
                    const ctxBar = document.getElementById('barChart').getContext('2d');
                    new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: data.kas_masuk.map(d => d.nama),
                            datasets: [{
                                label: 'Kas Masuk',
                                data: data.kas_masuk.map(d => d.total),
                                backgroundColor: ['#a87bc2', '#b28dd0', '#9e9e9e', '#000000', '#6c3483', '#c0c0c0'],
                                borderRadius: 10,
                                barThickness: 30
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => 'Rp. ' + (ctx.parsed.y || 0).toLocaleString('id-ID')
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: { color: '#3d3d3d', font: { weight: 'bold' } },
                                    grid: { display: false }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#3d3d3d',
                                        callback: val => 'Rp. ' + val.toLocaleString('id-ID')
                                    },
                                    grid: { color: 'rgba(200, 200, 200, 0.2)', borderDash: [3, 3] }
                                }
                            }
                        }
                    });

                    // DONUT CHART
                    const donutCtx = document.getElementById('donutChart').getContext('2d');
                    const anggota = Number(data.donut.anggota) || 0;
                    const pendapatan = Number(data.donut.pendapatan) || 0;
                    const angsuran = Number(data.donut.angsuran) || 0;
                    const pinjaman = Number(data.donut.pinjaman) || 0;
                    const total = anggota + pendapatan + angsuran + pinjaman || 1;

                    const persenAnggota = ((anggota / total) * 100).toFixed(1);
                    const persenPendapatan = ((pendapatan / total) * 100).toFixed(1);
                    const persenAngsuran = ((angsuran / total) * 100).toFixed(1);
                    const persenPinjaman = ((pinjaman / total) * 100).toFixed(1);

                    Chart.defaults.elements.arc.borderRadius = 10;

                    new Chart(donutCtx, {
                        type: 'doughnut',
                        data: {
                            labels: [
                                `Anggota ${persenAnggota}%`,
                                `Pendapatan ${persenPendapatan}%`,
                                `Angsuran ${persenAngsuran}%`,
                                `Pinjaman ${persenPinjaman}%`
                            ],
                            datasets: [{
                                data: [anggota, pendapatan, angsuran, pinjaman],
                                backgroundColor: ['#333', '#87cefa', '#3d3d3d', '#b28dd0'],
                                borderRadius: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            cutout: '60%',
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: { color: '#333', font: { size: 12 } }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => {
                                            const val = ctx.parsed;
                                            const pct = ((val / total) * 100).toFixed(1);
                                            return `${pct}% (Rp. ${val.toLocaleString('id-ID')})`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });

            // Update angka info card secara dinamis dari API
            function formatCurrency(amount) {
                return amount.toLocaleString('id-ID');
            }

            fetch('/anggota/active-count')
                .then(res => res.json())
                .then(data => document.querySelector('#jumlahAnggota').textContent = data.active_members || 0);

            fetch('/simpanan/total')
                .then(res => res.json())
                .then(data => document.querySelector('#jumlahSimpanan').textContent = formatCurrency(data.total_simpanan || 0));

            fetch('/angsuran/total')
                .then(res => res.json())
                .then(data => document.querySelector('#jumlahAngsuran').textContent = formatCurrency(data.total_angsuran || 0));

            fetch('/pinjaman/total')
                .then(res => res.json())
                .then(data => document.querySelector('#jumlahPinjaman').textContent = formatCurrency(data.total_pinjaman || 0));

            // Fetch dan tampilkan daftar kredit macet
            fetch('/dashboard/kredit-macet')
                .then(res => res.json())
                .then(data => {
                    const list = document.querySelector('#kreditMacetItems');
                    list.innerHTML = '';
                    if (data.length === 0) {
                        list.innerHTML = '<li>Tidak ada data kredit macet.</li>';
                        return;
                    }
                    const colors = ['#6a1b9a', '#ab47bc', '#ce93d8', '#f3e5f5', '#d1c4e9', '#9575cd'];
                    data.forEach((item, i) => {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong style="color:${colors[i % colors.length]};">▮</strong> ${item.nama} - ${item.total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
                        list.appendChild(li);
                    });
                })
                .catch(() => {
                    document.querySelector('#kreditMacetItems').innerHTML = '<li>Gagal mengambil data kredit macet.</li>';
                });
        });
        // Fetch dan tampilkan total bunga (sudah termasuk asuransi)
        fetch('/dashboard/total-bunga')
            .then(res => res.json())
            .then(data => {
                // data.total_bunga sudah diformat dari PHP, jadi langsung tampilkan
                document.querySelector('#totalBunga').textContent = data.total_bunga; 
            })
            .catch((error) => { // Tambahkan parameter error untuk logging di console
                console.error('Gagal memuat total bunga:', error); // Log error ke konsol browser
                document.querySelector('#totalBunga').textContent = 'Gagal memuat';
            });
        
                /*
        fetch('/dashboard/total-potongan-asuransi')
            .then(res => res.json())
            .then(data => {
                document.querySelector('#totalAsuransi').textContent = data.total_potongan_asuransi; 
            })
            .catch((error) => {
                console.error('Gagal memuat total potongan asuransi:', error);
                document.querySelector('#totalAsuransi').textContent = 'Gagal memuat';
            });
        */
    </script>

@endsection