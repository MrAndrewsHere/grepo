<template>
  <div id="app">

      <router-view :key="$route.fullPath"/>

  </div>
</template>

<script>


export default {
  name: 'App',
};
</script>
<style>
.custom_overline{
  font-size: .625rem!important;
  font-weight: 400;
  letter-spacing: .1666666667em!important;
  line-height: 1rem;
  text-transform: uppercase;
  font-family: Roboto,sans-serif!important;
}
</style>
