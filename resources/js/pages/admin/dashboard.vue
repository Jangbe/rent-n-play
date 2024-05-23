<script setup>
import { onMounted, ref, watch } from 'vue';
import { number_format } from '../../helpers';

const filters = ref({ transaction_filter: 'DAY', income_filter: 'MONTH', customer_filter: 'YEAR', report_filter: 'DAY', product_filter: 'DAY' });
const data = ref({ transaction: { current: 0, last: 0 }, income: { current: 0, last: 0 }, customer: { current: 0, last: 0 } });
const refetch = () => axios.get('dashboard/admin', { params: filters.value }).then(({ data: res }) => {
    for (const i in res) {
        if (['reports', 'products'].includes(i)) continue;
        res[i].percent = percent(res[i]);
        res[i].color = res[i].current > res[i].last ? 'success' : res[i].current < res[i].last ? 'danger' : 'secondary';
        res[i].text = res[i].current > res[i].last ? 'meningkat' : res[i].current < res[i].last ? 'menurun' : 'tetap';
    }
    data.value = res;
    chart.updateOptions({
        xaxis: { categories: res.reports.categories },
        series: res.reports.series
    });
})
refetch();
watch(filters, refetch, { deep: true });
const filter_data = {
    DAY: 'Hari Ini',
    MONTH: 'Bulan Ini',
    YEAR: 'Tahun Ini',
}
const percent = (data) => {
    const diff = Math.abs(data.last - data.current);
    return data.last == 0 ? 100 : (diff / data.last * 100).toPrecision(2);
}
var chart = null;
onMounted(() => {
    chart = new ApexCharts(document.querySelector("#reportsChart"), {
        series: [],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false
            },
        },
        markers: {
            size: 4
        },
        colors: ['#4154f1', '#2eca6a', '#ff771d'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'datetime',
            categories: []
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
            y: [
                {},
                { formatter: (val) => val * 10000 },
                {}
            ]
        }
    });
    chart.render();
})
</script>

<template>
    <div>
        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <div class="col-xxl-4 col-md-4 order-md-2">
                    <!-- Transaction Card -->
                    <div class="card mb-3 bg-white info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li v-for="(s, i) in filter_data" @click="filters.transaction_filter = i">
                                    <a class="dropdown-item" href="#">{{ s }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Transaksi
                                <span>| {{ filter_data[filters.transaction_filter] }}</span>
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ data.transaction.current }}</h6>
                                    <span :class="`text-${data.transaction.color ?? 'secondary'} small pt-1 fw-bold`">
                                        {{ data.transaction.percent }}%</span>
                                    <span class="text-muted small pt-2 ps-1">
                                        {{ data.transaction.text ?? 'tetap' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div><!-- End Transaction Card -->

                    <!-- Income Card -->
                    <div class="card mb-3 bg-white info-card revenue-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li v-for="(s, i) in filter_data" @click="filters.income_filter = i">
                                    <a class="dropdown-item" href="#">{{ s }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Pendapatan
                                <span>| {{ filter_data[filters.income_filter] }}</span>
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ number_format(data.income.current) }}</h6>
                                    <span :class="`text-${data.income.color ?? 'secondary'} small pt-1 fw-bold`">{{
                                        data.income.percent }}%</span>
                                    <span class="text-muted small pt-2 ps-1">
                                        {{ data.income.text ?? 'tetap' }}
                                    </span>

                                </div>
                            </div>
                        </div>

                    </div><!-- End Income Card -->

                    <!-- Customers Card -->
                    <div class="card mb-3 bg-white info-card customers-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li v-for="(s, i) in filter_data" @click="filters.customer_filter = i">
                                    <a class="dropdown-item" href="#">{{ s }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Pengguna
                                <span>| {{ filter_data[filters.customer_filter] }}</span>
                            </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ data.customer.current }}</h6>
                                    <span :class="`text-${data.customer.color ?? 'secondary'} small pt-1 fw-bold`">{{
                                        data.customer.percent }}%</span>
                                    <span class="text-muted small pt-2 ps-1">
                                        {{ data.customer.text ?? 'tetap' }}
                                    </span>

                                </div>
                            </div>

                        </div>
                    </div><!-- End Customers Card -->
                </div>

                <div class="col-xxl-8 col-md-8 col-12 order-md-1">
                    <!-- Reports -->
                    <div class="card bg-white mb-3">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li v-for="(s, i) in filter_data" @click="filters.report_filter = i">
                                    <a class="dropdown-item" href="#">{{ s }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Laporan <span>/{{ filter_data[filters.report_filter] }}</span></h5>

                            <!-- Line Chart -->
                            <div id="reportsChart"></div>
                            <!-- End Line Chart -->

                        </div>

                    </div><!-- End Reports -->

                    <!-- Popular Product -->
                    <div class="card bg-white top-selling overflow-auto">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li v-for="(s, i) in filter_data" @click="filters.product_filter = i">
                                    <a class="dropdown-item" href="#">{{ s }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Barang Terpopuler
                                <span>| {{ filter_data[filters.product_filter] }}</span>
                            </h5>

                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Pratinjau</th>
                                        <th scope="col">Barang</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Ter-sewa</th>
                                        <th scope="col">Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="p in data.products ?? []">
                                        <th scope="row"><a href="#"><img :src="'/storage/' + p.picture" alt=""></a></th>
                                        <td><a href="#" class="text-primary fw-bold">{{ p.name }}</a></td>
                                        <td>{{ number_format(p.price) }}</td>
                                        <td class="fw-bold">{{ p.sold }}</td>
                                        <td>{{ number_format(p.revenue) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div><!-- End Popular Product -->
                </div>

            </div>
        </section>
    </div>
</template>

<route lang="yaml">
    meta:
        layout: admin
</route>
