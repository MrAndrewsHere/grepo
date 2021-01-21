<template>
  <main>
   <v-container>
     <v-card>
       <v-card-title class="indigo white--text headline">
         Group Ranks
       </v-card-title>
       <v-row
           class="pa-4"
           justify="space-between"
       >
         <v-col cols="5" style="height: 90vh; overflow-y: scroll">
           <v-treeview




               :active.sync="active"
               :items="data"
               :load-children="fetchUsers"
               :open.sync="open"
               dense
               activatable
               color="warning"

               transition
           >
             <template v-slot:prepend="{  }">
               <v-icon >
                 mdi-account
               </v-icon>
             </template>
             <template v-slot:label="{item}">
               <span style="cursor: pointer">{{item.name}}</span>
             </template>

           </v-treeview>
         </v-col>

         <v-divider vertical></v-divider>

         <v-col
             class="d-flex text-center"
         >
           <v-scroll-y-transition mode="out-in">
             <div
                 v-if="!selected"
                 style="text-align: center"
             >
             Выберите что-то
             </div>
             <v-simple-table v-if="selected" light class="pa-2"   style="background: transparent;box-shadow: none" >
               <template v-slot:default>
                 <thead>
                 <tr>
                   <th class="">
                     Группа
                   </th>
                   <th class="text-left">
                     Логин
                   </th>
                   <th class="text-left">
                     Фио
                   </th>
                   <th class="text-left">
                     Факультет
                   </th>
                   <th class="text-left">
                     Направление
                   </th>
                 </tr>
                 </thead>
                 <tbody>
                 <tr
                     v-for="(item,index) in selected"
                     :key="index"
                 >
                   <td>{{ item.groupName}}</td>
                   <td>{{ item.login}}</td>
                   <td>{{ item.fullName}}</td>
                   <td>{{ item.department}}</td>
                   <td>{{ item.direction}}</td>

                 </tr>
                 </tbody>
               </template>
             </v-simple-table>
           </v-scroll-y-transition>


         </v-col>
       </v-row>
     </v-card>
   </v-container>

  </main>

</template>

<script>
import {HTTP} from "../axios";


export default {
  name: "groupRank",
  data: () => {
    return {
      active: [],
      activeGroup:[],
      avatar: null,
      open: [],
      users: [],
      data:[]
    }


  },

  computed: {
    selected () {
      if (!this.active.length) return undefined
      if (!this.open.length) return undefined

      const groupId = this.active[0]

      const rankId = this.open.filter(el => {


       return  this.data.find(rank => rank.id === el).children.some( s => {
          return s.id === groupId;
        })
      })[0]

      return this.data.find(rank => rank.id === rankId).children.find(group => group.id === groupId).childrens || undefined;
    },

  },
  methods:{
    getDada(){
      HTTP.get('/groupRanks').then(res => {
        this.data = Object.values(res.data);
      })
    },
    fetchUsers(item){
      console.log(item);
    }

  },
  mounted() {
     this.getDada();
  }
}
</script>

<style scoped>

</style>