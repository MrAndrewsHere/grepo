<template>

  <div class="container">
    <v-progress-linear   v-if="!loaded && !error" indeterminate rounded ></v-progress-linear>

    <div v-if="loaded && error" style="text-align: center;color: darkred">
      Не удалось получить данные
    </div>
    <div v-if="loaded && !error" >
      <div class="row justify-space-between mx-2 align-center justify-start">
        <div class="title font-weight-bold  ">
          Динамика процесса обyчения средствами ВКС
        </div>
        <div class=" d-flex justify-end  ml-3 py-0" >
          <div class="pa-1">
            <div tabindex="-1" class="text-center px-1 v-list-item v-list-item--two-line theme--light">
              <div class="v-list-item__content py-0">
                <div class="v-list-item__title">
                  <i aria-hidden="true"
                     class="v-icon notranslate mdi mdi-chart-timeline-variant theme--light"
                     style="color: rgb(0, 150, 136); caret-color: rgb(0, 150, 136);">

                  </i>
                </div>
                <div class="v-list-item__subtitle">студенты</div>
              </div>
            </div>
          </div>
          <div class="pa-1">
            <div tabindex="-1" class="text-center px-1 v-list-item v-list-item--two-line theme--light">
              <div class="v-list-item__content py-0">
                <div class="v-list-item__title">
                  <i aria-hidden="true" class="v-icon notranslate mdi mdi-chart-timeline-variant theme--light"
                     style="color: rgb(0, 0, 0); caret-color: rgb(0, 0, 0);">

                  </i>
                </div>
                <div class="v-list-item__subtitle">занятия</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-5">
        <line-chart v-if="loaded  && !error" :chart-data="LineChartData" :options="LineChartOptions"/>
      </div>
      <div class="row justify-space-between mx-2 align-center justify-start mb-5">
        <div class="title font-weight-bold  ">
          Посещаемость (%)
        </div>

      </div>
      <div>
        <bar-chart v-if="loaded  && !error" :chart-data="BarChartData" :options="BarChartOptions"></bar-chart>
      </div>
    </div>


  </div>
</template>

<script>
import LineChart from "./charts/LineChart";
import BarChart from "./charts/BarChart";
import {HTTP} from "../axios/index";

export default {
  name: "dynamics",
  components: {BarChart, LineChart},
  data: () => ({
    loaded: false,
    error:false,
    LineChartData: {
      labels: [
        "12.10",
        "13.10",
        "14.10",
        "15.10",
        "16.10",
        "17.10",
        "18.10",
        "19.10",
        "20.10",
        "21.10",
        "22.10",
        "23.10",
        "24.10",
        "25.10",
        "26.10",
        "27.10",
        "28.10",
        "29.10",
        "30.10",
        "01.11",
        "02.11",
        "03.11",
        "04.11",
        "05.11",
        "06.11",
        "07.11",
        "08.11",
        "09.11",
        "10.11",
        "11.11",
        "12.11"
      ],
      datasets: [
        {
          label: 'Занятия',
          data: [
            161,
            192,
            183,
            178,
            184,
            101,
            31,
            230,
            239,
            245,
            225,
            235,
            103,
            28,
            236,
            243,
            255,
            224,
            214,
            33,
            250,
            255,
            35,
            231,
            179,
            127,
            45,
            263,
            267,
            247,
            213
          ],
          fill: false,
          borderColor: '#000000',
          backgroundColor: '#000000',
          borderWidth: 1
        },
        {
          label: 'Студенты',
          data: [
            1672,
            1711,
            1725,
            1742,
            1855,
            1219,
            160,
            2220,
            2325,
            2341,
            2156,
            2304,
            1116,
            131,
            2133,
            2237,
            2496,
            2298,
            2086,
            158,
            2466,
            2560,
            94,
            2399,
            1482,
            1238,
            276,
            2811,
            2813,
            2628,
            2523
          ],
          fill: false,
          borderColor: 'rgb(0, 150, 136)',
          backgroundColor: 'rgb(0, 150, 136)',
          borderWidth: 1
        }
      ]
    },
    LineChartOptions: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          },
          gridLines: {
            display: true
          }
        }],
        xAxes: [{
          gridLines: {
            display: false
          }
        }]
      },
      legend: {
        display: false
      },
      responsive: true,
      maintainAspectRatio: false
    },
    BarChartData:{
      labels: [],
      datasets: [
        {
          label: '%',
          backgroundColor: '#4b47ff',
          data: [],

        }
      ]
    },
   BarChartOptions:{
     responsive: true,
     maintainAspectRatio: false,
     legend: {
       display: false
     },
   }
  }),
  async mounted() {
    this.loaded = false
    try {
     HTTP.get('/linechart').then(res => {
       this.LineChartData.labels = res.data.labels
       this.LineChartData.datasets[0].data = res.data.values.pairs
       this.LineChartData.datasets[1].data = res.data.values.students
       HTTP.get('/barchart').then(res => {
         this.BarChartData.labels = res.data.labels;
         this.BarChartData.datasets[0].data = res.data.dataset;

       }).catch(()=>{ this.error = true; this.loaded = true }).finally( () => {
         this.loaded = true
       })


     }).catch(()=>{ this.error = true;  this.loaded = true})


    } catch (e) {
      console.error(e)
    }
  }
}
</script>

<style scoped>

</style>