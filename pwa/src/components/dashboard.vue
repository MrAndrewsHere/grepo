<template>
  <v-app>
    <v-navigation-drawer
        v-model="drawer"
        app
        clipped
    >
      <short-stats></short-stats>

      <v-list  dense>
        <v-list-item link  v-on:click="activeComponent='dynamics'">

          <v-list-item-icon>
            <v-icon>
              mdi mdi-chart-line
            </v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title  >Динамика</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link  v-on:click="activeComponent='attendance'">
          <v-list-item-icon>
            <v-icon>
              mdi mdi-account-group
            </v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title  >Посещаемость</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link v-on:click="activeComponent='PairsLessHour'">
          <v-list-item-icon>
            <v-icon>
              mdi-progress-clock
            </v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title >Занятия менее часа</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </v-list>
    </v-navigation-drawer>
    <v-app-bar
        app
        clipped-left
        color="white"
    >
      <v-avatar @click="drawer = !drawer" size="54px" >
        <img
            src="/img/logo_new_small_eng_100.png"
            alt="Логотип">
      </v-avatar>
      <v-toolbar-title >
        <v-list-item >
          <v-list-item-content>
            <v-list-item-title class="title">
              Ситуационный центр
            </v-list-item-title>
            <v-list-item-subtitle>
              Университет Дубна
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-list-item class="pa-0">
          <v-list-item-content style="text-align: right">
            <v-list-item-title class="body-1">
             {{user.login}}
            </v-list-item-title>
            <v-list-item-subtitle >
             <span v-if="isLoggedIn"  @click="logout" class="logout_btn" style="cursor: pointer"> Выйти</span>
            </v-list-item-subtitle>
          </v-list-item-content>
<!--          <v-list-item-icon>-->
<!--            <v-icon>mdi-account</v-icon>-->
<!--          </v-list-item-icon>-->
        </v-list-item>
      </v-toolbar-items>


    </v-app-bar>
    <v-main>
      <v-container fluid class="pa2">
        <keep-alive>
          <component :is="activeComponent"  ></component>
        </keep-alive>
      </v-container>

    </v-main>

  </v-app>
</template>

<script>
import attendance from "./attendance";
import dynamics from "./dynamics";
import PairsLessHour from "./PairsLessHour";
import ShortStats from "./shortStats";


export default {
  name: 'dashboard',

  components: {
    ShortStats,
    dynamics,
    PairsLessHour,
    attendance,
  },
  computed : {
    isLoggedIn : function(){ return this.$store.getters.isLoggedIn},
    user:function(){ return this.$store.getters.authUser},

  },
  methods: {
    logout: function () {
      this.$store.dispatch('logout')
          .then(() => {
            this.$router.push('/login')
          })
    }
  },
  data: () => ({
    drawer:true,
    activeComponent:'dynamics',
  }),
};
</script>
<style>
.logout_btn{
  cursor: pointer;
}
.logout_btn:hover{
  color:black;
}
.custom_overline{
  font-size: .625rem!important;
  font-weight: 400;
  letter-spacing: .1666666667em!important;
  line-height: 1rem;
  text-transform: uppercase;
  font-family: Roboto,sans-serif!important;
}
</style>
