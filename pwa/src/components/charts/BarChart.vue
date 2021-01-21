<script>
import { Bar } from 'vue-chartjs'

export default {
name: "BarChart",
  extends: Bar,
  props:['chartData','options'],
  data: () => {
  return {
  }
  },

  mounted () {
    this.renderChart(this.chartData, this.options)
  }
}
</script>

<style scoped>

</style>