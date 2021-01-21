<template>
  <v-card
      class="mx-auto"
      max-width="400"
      tile
  >
    <v-list>
      <digital-clock></digital-clock>
      <v-divider class="mx-4"></v-divider>
      <v-list-item two-line>
        <v-list-item-icon>
          <v-icon>mdi-monitor-cellphone</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            <span class="title font-weight-bold mr-2">{{stats.today[0].cnt_pairs40}}</span>
            <span class="text--disabled font-weight-light body-2">| {{stats.yesterday[0].cnt_pairs40}} </span>
            <span class="text--disabled font-weight-light caption">вчера </span>

          </v-list-item-title>


          <v-list-item-subtitle class="custom_overline">занятий</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-icon>
          <v-icon>mdi-account-circle-outline</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            <span class="title font-weight-bold mr-2">{{stats.today[0].cnt_sudents40}}</span>
            <span class="text--disabled font-weight-light body-2">| {{stats.yesterday[0].cnt_sudents40}} </span>
            <span class="text--disabled font-weight-light caption">вчера </span>
          </v-list-item-title>
          <v-list-item-subtitle class="custom_overline">студентов</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-icon>
          <v-icon>mdi-percent</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            <span class="title font-weight-bold mr-2"> {{stats.p_today[0].p||0}}</span>

          </v-list-item-title>
          <v-list-item-subtitle class="custom_overline">Посещаемость</v-list-item-subtitle>
          <v-list-item-subtitle class="custom_overline">за текущую неделю</v-list-item-subtitle>

        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-card>
</template>
<script>
import DigitalClock from "./digitalClock";
import {HTTP} from "../axios/index";

export default {
  name: "shortStats",
  components: {DigitalClock},
  data: () => {
    return {
      stats:{
        today: [
          {
            cnt_pairs15: 0,
            cnt_pairs40: 0,
            cnt_sudents15: 0,
            cnt_sudents40: 0
          }
        ],
        yesterday: [
          {
            cnt_pairs40: 0,
            cnt_sudents40: 0
          }
        ],
        yesterday_day: "2020-11-14",
        p_today: [
          {
            "start_day": "09.11",
            "end_day": "14.11",
            "avgs": null,
            "cnt": 0,
            p: 0
          }
        ]
      },
    }
  },
  mounted() {

    HTTP.get('/stats').then(res =>{
      this.stats = res.data
        }

    )
  }
}
</script>

<style scoped>

</style>