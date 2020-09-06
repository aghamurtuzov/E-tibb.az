<template>

  <div class="col-md-12" v-if="isnormal === 1">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog">
            <div class="input-group mbx-10">
              <input type="text" class="form-control" placeholder="Axtarış sözü"
                     aria-describedby="basic-addon1" v-model="searchInput" @keypress.enter="search">
            </div>
            <button type="button" class="btn-effect" @click = "search">
              <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else>
    <div class="search">
      <div class="block d-block">
        <div class="row">
          <div class="col-md-3 col-sm-4">
            <multiselect class="mbx-10"
                         v-model='SearchType.name'
                         label="name"
                         :internal-search="false"
                         :close-on-select="true"
                         :clear-on-select="false"
                         :hide-selected="false"
                         placeholder = "Axtarış növünü seçin"
                         :options="SearchType"
                         selectedLabel=""
                         deselectGroupLabel=""
                         deselectLabel=""
                         select-label=""
                         :reset-after="false"
                         :allow-empty="false"
                         track-by="id">
            </multiselect>
<!--            <select class="form-control selectpicker mbx-10" name="money" title="Axtarış növünü seçin">-->
<!--              <option value="">Qeydiyyat nömrəsinə görə</option>-->
<!--              <option value="">Əraziyə görə</option>-->
<!--              <option value="">Kartla ödəniş</option>-->
<!--              <option value="">Nəğd ödəniş</option>-->
<!--              <option value="">Premium</option>-->
<!--            </select>-->
          </div>
          <div class="col-md-9 col-sm-8">
            <div class="search-blog">
                <div class="input-group mbx-10">
                  <input type="text" class="form-control" placeholder="Axtarış sözü"
                         aria-describedby="basic-addon1" v-model="searchInput" @keypress.enter="search">
                </div>
                <button type="submit" class="btn-effect float-right" @click = "search">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect';
  export default {
    name: "Search",
    data: function(){
      return {
        searchInput: '',
        isnormal: 1,
        SearchType: [
          {id:1, name:'Qeydiyyat nömrəsinə görə'},
          {id:2, name:'Əraziyə görə'},
          {id:3, name:'Kartla ödəniş'},
          {id:4, name:'Nəğd ödəniş'},
          {id:5, name:'Premium'}
        ]
      }
    },
    components: {
      Multiselect
    },
    props: {
      isnormalsearch: Number
    },
    mounted: function () {
      this.isnormal = this.$props.isnormalsearch;
    },
    methods: {
      search() {
        this.$emit('SearchRequested', this.searchInput);
      }
    }
  }
</script>

<style scoped>

</style>
