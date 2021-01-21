<template>
  <v-list-item two-line>
    <v-list-item-content>
      <v-list-item-subtitle class="text-center custom_overline">{{ dayOfWeek }}</v-list-item-subtitle>
      <v-list-item-title class="text-center  font-weight-black headline py-1"><span style="letter-spacing: 0.05em;">
        {{ time }}
      </span>
      </v-list-item-title>
      <v-list-item-subtitle class="text-center custom_overline">{{date}}</v-list-item-subtitle>
    </v-list-item-content>

  </v-list-item>
</template>

<script>
export default {
  name: "digitalClock",
  data: () => {
    return {
      time: '',
      date: '',
      dayOfWeek:'',
      week :['Воскресенье','Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
      months:['января','февраля','марта','апреля','июня','июля','августа','сентября','октября','ноября','декабря'],

    }
  },
  methods:{
    updateTime() {
      var cd = new Date();
      this.time = this.zeroPadding(cd.getHours(), 2) + ':' + this.zeroPadding(cd.getMinutes(), 2);

      this.dayOfWeek = this.week[cd.getDay()]
      this.date = this.zeroPadding(cd.getDate(), 2)+ ' '+  this.months[this.zeroPadding(cd.getMonth()+1, 2)-2]+' '+this.zeroPadding(cd.getFullYear(), 4) ;
    },
    zeroPadding(num, digit) {
      var zero = '';
      for(var i = 0; i < digit; i++) {
        zero += '0';
      }
      return (zero + num).slice(-digit);
    }
  },
  mounted() {
    this.updateTime();
    setInterval(this.updateTime, 1000);
  }
}
</script>

<style scoped>

</style>