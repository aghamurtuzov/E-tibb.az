<template>
  <div id="app">
    <Head/>
    <Layout/>
  </div>
</template>

<script>

import Head from './components/Head'
import Layout from './components/Layout'
import axios_main from 'axios';

export default {
  name: 'app',
  components: {
    Head,
    Layout
  }
}
</script>

