<div>
  <section class="row">
    <div class="col-12 col-lg-9">
      <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4-5">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                  <div class="stats-icon purple mb-2">
                    <i class="iconly-boldProfile"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Pelajar</h6>
                  <h6 class="font-extrabold mb-0">{{ $charts['counter']['student'] }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4-5">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                  <div class="stats-icon blue mb-2">
                    <i class="iconly-boldBookmark"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Kelas</h6>
                  <h6 class="font-extrabold mb-0">{{ $charts['counter']['schoolClass'] }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4-5">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                  <div class="stats-icon green mb-2">
                    <i class="iconly-boldBag"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Jurusan</h6>
                  <h6 class="font-extrabold mb-0">{{ $charts['counter']['schoolMajor'] }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4-5">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                  <div class="stats-icon red mb-2">
                    <i class="iconly-boldProfile"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Administrator</h6>
                  <h6 class="font-extrabold mb-0">{{ $charts['counter']['administrator'] }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 id="card-chart-deposits-title">Total Transaksi Tahun Ini</h4>
              <div class="mb-3">
                <label for="year" class="form-label">Isi Tahun:</label>
                <input wire:model="year" wire:keyup.enter="updateChart" type="number" id="year"
                  placeholder="Masukan tahun.." value="{{ date('Y') }}" class="form-control">
                <div class="form-text">Tekan tombol `Enter` untuk menampilkan grafik berdasarkan tahun yang dipilih.
                </div>
              </div>
            </div>
            <div wire:ignore class="card-body">
              <div id="deposit-chart-bar-by-year"></div>
            </div>
            <div wire:ignore class="card-body">
              <div id="deposit-chart-line-by-year"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <x-apexcharts.line-chart chartTitle="Total Transaksi Per Tahun" seriesTitle="Total Transaksi"
            chartID="chart-deposits-count-per-year"
            :series="$charts['lineChart']['depositCountPerYear']['series']"
            :categories="$charts['lineChart']['depositCountPerYear']['categories']" />
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <x-apexcharts.line-chart chartTitle="Total Jumlah Pembayaran Transaksi Per Tahun"
            seriesTitle="Total Pembayaran" chartID="chart-deposits-amount-per-year"
            :series="$charts['lineChart']['depositAmountPerYear']['series']"
            :categories="$charts['lineChart']['depositAmountPerYear']['categories']" />
        </div>
      </div>

    </div>
    <div class="col-12 col-lg-3">
      <div class="card">
        <div class="card-body py-4 px-4">
          <div class="d-flex align-items-center">
            <div class="avatar avatar-xl">
              <img src="{{ asset('compiled/jpg/1.jpg') }}" alt="Face 1">
            </div>
            <div class="ms-3 name">
              <h5 class="font-bold">{{ auth()->user()->name }}</h5>
              <h6 class="text-muted mb-0">{{ auth()->user()->email }}</h6>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <x-apexcharts.pie-chart chartTitle="Pelajar Berdasarkan Jenis Kelamin" chartID="chart-pie-student-gender"
          :series="$charts['pieChart']['studentGender']['series']"
          :labels="$charts['pieChart']['studentGender']['labels']" :colors="['#57CAEB', '#FF7976']" />
      </div>
      <div class="card">
        <x-apexcharts.pie-chart chartTitle="Pelajar Berdasarkan Jurusan" chartID="chart-pie-student-school-major"
          :series="$charts['pieChart']['studentMajor']['series']"
          :labels="$charts['pieChart']['studentMajor']['labels']" />
      </div>
      <div class="card">
        <x-apexcharts.pie-chart chartTitle="Total Transaksi Berdasarkan Jenis Kelamin"
          chartID="chart-pie-deposit-by-gender"
          :series="$charts['pieChart']['depositCountByGender']['series']"
          :labels="$charts['pieChart']['depositCountByGender']['labels']" :colors="['#57CAEB', '#FF7976']" />
      </div>
    </div>
  </section>
</div>

@push('scripts')
<script>
  document.addEventListener('livewire:init', () => {
    let depositBarChart;

    let depositLineChart;

    const categories = [
      "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
      "Jul", "Agu", "Sep", "Okt", "Nov", "Des",
    ];

    Livewire.on('dashboard-chart-loaded', (e) => {
      const { amount, count } = e;

      const barChartOptions = {
        chart: {
          type: "bar",
          height: 250,
        },
        series: [
          {
            name: "Total Transaksi",
            data: [
              count.jan,
              count.feb,
              count.mar,
              count.apr,
              count.mei,
              count.jun,
              count.jul,
              count.agu,
              count.sep,
              count.okt,
              count.nov,
              count.des,
            ],
          },
        ],
        colors: ["#435ebe"],
        xaxis: {
          categories: categories,
        },
      };

      depositBarChart = new ApexCharts(
        document.querySelector("#deposit-chart-bar-by-year"),
        barChartOptions
      );

      depositBarChart.render();

      const lineChartOptions = {
        series: [
          {
            name: "Jumlah Pembayaran",
            data: Object.values(amount),
          },
        ],
        chart: {
          height: 250,
          type: "line",
          zoom: {
            enabled: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "straight",
        },
        grid: {
          row: {
            colors: ["#f3f3f3", "transparent"],
            opacity: 0.5,
          },
        },
        xaxis: {
          categories: categories,
        },
      };

      depositLineChart = new ApexCharts(
        document.querySelector("#deposit-chart-line-by-year"),
        lineChartOptions
      );
      depositLineChart.render();
    })

    Livewire.on('dashboard-chart-updated', (e) => {
      const { amount, count } = e;

      depositBarChart.updateSeries([
        {
          data: [
            count.jan,
            count.feb,
            count.mar,
            count.apr,
            count.mei,
            count.jun,
            count.jul,
            count.agu,
            count.sep,
            count.okt,
            count.nov,
            count.des,
          ],
        }
      ]);

      depositLineChart.updateSeries([
        {
          data: [
            amount.jan,
            amount.feb,
            amount.mar,
            amount.apr,
            amount.mei,
            amount.jun,
            amount.jul,
            amount.agu,
            amount.sep,
            amount.okt,
            amount.nov,
            amount.des,
          ],
        }
      ]);
    });
  });
</script>
@endpush
