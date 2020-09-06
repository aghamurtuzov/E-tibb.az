<template>
  <div id="doctor_app">
    <div>
      <Head/>
      <Layout/>
    </div>
  </div>
</template>

<script>

import Head from './doctor_components/Head'
import Layout from './doctor_components/Layout'

export default {
  name: 'Doctor_app',
  data:function () {
    return {
      test_route: ''
    }
  },
  components: {
    Head,
    Layout
  }
}
</script>

