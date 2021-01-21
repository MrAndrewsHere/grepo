<template>
  <v-card>
    <v-card-title>
      <v-row justify="end">
        <v-col  cols="12"
                sm="4"
                md="3">
        <v-switch
            v-model="groupExists"
            label="Только с группой"
            color="red"


            hide-details
        ></v-switch>
        </v-col>
        <v-spacer></v-spacer>
        <v-col  cols="12"
                sm="4"
                md="3">
          <v-text-field
              label="Мин. кол-во участников"
              placeholder="Количество"
              v-model="more2people"
              color="black"
          >
            <template slot="prepend">
              <v-icon>mdi-account</v-icon>
            </template>
          </v-text-field>

        </v-col>

        <v-col
            cols="12"
            sm="3"
            md="2"
        >
          <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              :return-value.sync="date"
              transition="scale-transition"
              offset-y
              min-width="290px"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                  v-model="date"
                  label="Picker in menu"

                  readonly
                  single-line
                  hide-details
                  v-bind="attrs"
                  v-on="on"
              >
                <template slot="prepend-inner">
                  <v-icon>mdi-calendar</v-icon>
                </template>
              </v-text-field>
            </template>
            <v-date-picker
                v-model="date"
                no-title
                scrollable
            >
              <v-spacer></v-spacer>
              <v-btn
                  text
                  color="primary"
                  @click="menu = false"
              >
                Cancel
              </v-btn>
              <v-btn
                  text
                  color="primary"
                  @click="$refs.menu.save(date),getAttendanceData()"
              >
                OK
              </v-btn>
            </v-date-picker>
          </v-menu>
        </v-col>
        <v-col
            cols="12"
            sm="4"
            md="3"
        >
          <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Найти"
              single-line
              hide-details
          ></v-text-field>
        </v-col>
      </v-row>


    </v-card-title>
    <v-data-table class="px-3"
        :headers="headers"
        :items="attendanceData"

                  :loading="loading"
                  loading-text="Loading... Please wait"
        :search="search"
               show-expand

                  item-key="id"
                  group-by="groups"
                  sort-by="pair"
    >
      <template v-slot:expanded-item="{ item }">
        <td colspan="6">
          <v-simple-table light class="pa-2"   style="background: transparent;box-shadow: none" >
            <template v-slot:default>
              <thead>
              <tr>
                <th class="text-left">
                  Имя
                </th>
                <th class="text-left">
                  Группа
                </th>
                <th class="text-left">
                  Email
                </th>
                <th class="text-left">
                  подкл.
                </th>
                <th class="text-left">
                  откл.
                </th>
                <th class="text-left">
                  Продолжительность
                </th>
              </tr>
              </thead>
              <tbody>
              <tr
                  v-for="(member,index) in item.members"
                  :key="index"
              >
                <td>{{ member.member_name }}</td>
                <td>{{ member.lecturer_id?'Преподаватель':member.groupname }}</td>
                <td>{{ member.member_email }}</td>
                <td>{{ member.start_time }}</td>
                <td>{{ member.time }}</td>
                <td>{{ member.duration }} мин.</td>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
        </td>


      </template>
      <template v-slot:item.pair="{ item }">
        {{ item.pair }}
        <v-chip x-small v- v-if="item.lecturer" class="Caption" >
       {{item.lecturer.duration+' мин.'}}
      </v-chip>
      </template>

      <template v-slot:item.lecturer="{ item }">
        {{ item.lecturer? item.lecturer.member_name:'' }}

      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import {HTTP} from "../axios/index";

export default {
  name: "attendance",
  props:['groupby'],
  data () {
    return {
      date: new Date().toISOString().substr(0, 10),
      loading:false,
      menu: false,
      more2people:2,
      search: '',
      groupExists:false,
      headers: [
        {
          text: 'Пара',
          align: 'start',
          filterable: false,
          value: 'pair',
        },

        { text: 'Преподаватель', value: 'lecturer' },
        { text: 'Группа', value: 'groups' },
        { text: 'Кол-во участиков', value: 'members_count' },
        { text: 'Дата', value: 'date' },
        { text: 'Код встречи', value: 'meet_code' },
      ],
      data:[],

    }
  },

  computed:{
    attendanceData:{
      get(){
        return this.data.filter( el => {
          return el.members_count >= this.more2people && (this.groupExists?(el.groups):true) ;
        });
      }
    }
  },

  methods:{
    getAttendanceData(data){
      var  temp = data?data:this.date
      this.loading = true;

      HTTP.get('/attendance/'+temp).then( res => {
        this.data = Object.values(res.data)
      }).finally(()=>{
        this.loading = false
      })
    }
  },
  mounted() {
    this.getAttendanceData(new Date().toISOString().substr(0, 10))

  }

}
</script>

<style scoped>

</style>