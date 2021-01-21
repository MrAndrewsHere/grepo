<template>

  <div
      class="container3"


  >
   <div class="container4" >

     <v-card  class="elevation-5 ">

       <v-form style="margin: 10px;padding-top: 20px">
         <v-text-field

             required
             label="Логин"
             name="login"

             prepend-inner-icon="mdi-account"
             type="text"
             v-model="login"

             :messages="error.login"
             :rules="[v => !!v || 'Login is required']"
         ></v-text-field>

         <v-text-field
             required
             id="password"
             label="Пароль"
             name="password"
             prepend-inner-icon="mdi-lock"

             type="password"
             v-model="password"

             :rules="[v => !!v || 'Password is required']"
             :messages="error.password"
         ></v-text-field>
       </v-form>
       <div class="pa-3" v-if="error.message ">
         <v-alert type="error"  dense
                  outlined>

           <span >{{error.message}}</span>
         </v-alert>
       </div>
       <v-card-actions>
         <v-spacer></v-spacer>
         <v-btn color="black" @click="auth">Вход</v-btn>
       </v-card-actions>
     </v-card>
   </div>
  </div>

</template>

<script>
export default {
  name: "login",
  data(){
    return {
      login : "",
      password : "",
      error:"",
      loading:false,

    }
  },
  methods: {
    auth: function () {
      let login = this.login
      let password = this.password
      this.$store.dispatch('login', { login, password })
          .then(() => this.$router.push('/'))
          .catch(err =>
          {
            if(!err.response.status)
            {
              this.error.message = 'Не удалось выполнить авторизацию. Попобуйте позже'
            }
            this.error = err.response.data
          })
    }
  },
  mounted() {

  }
}
</script>

<style scoped>
div.container4 {
  height: 10em;
  width: 26em;

  position: relative }
div.container3 {
  margin: 0;

  position: absolute;
  top: 50%;
  left: 50%;
  margin-right: -50%;
  transform: translate(-50%, -50%) }
</style>