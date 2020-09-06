<template>
  <div class="col-md-12" v-if="isnormal === 'doctor'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="input-group mb-10 mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Ad" aria-describedby="basic-addon1" v-model="doctor.name">
                  </div>
                  <span class="validation" v-if="errors_doctor_search.name">{{errors_doctor_search.name}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="input-group mb-10 mbx-10">
                  <div>
                    <input type="email" class="form-control" placeholder="Email" aria-describedby="basic-addon1" v-model="doctor.email">
                  </div>
                  <span class="validation" v-if="errors_doctor_search.email">{{errors_doctor_search.email}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="input-group mb-10 mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Telefon" aria-describedby="basic-addon1" v-model="doctor.number">
                  </div>
                  <span class="validation" v-if="errors_doctor_search.number">{{errors_doctor_search.number}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Qeydiyyat nömrəsi" aria-describedby="basic-addon1" v-model="doctor.code">
                  </div>
                  <span class="validation" v-if="errors_doctor_search.code">{{errors_doctor_search.code}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="input-group mbx-10">
                  <div>
                    <multiselect v-model="doctor.specialist"
                                 label="name"
                                 :options="doctorSpecialists"
                                 track-by="id"
                                 placeholder="İxtisas"
                                 :multiple="false"
                                 :taggable="true"
                                 selectedLabel=""
                                 deselectGroupLabel=""
                                 deselectLabel=""
                                 select-label=""
                                 :reset-after="false"
                                 :allow-empty="false">
                    </multiselect>
                  </div>
                  <span class="validation" v-if="errors_doctor_search.specialist">{{errors_doctor_search.specialist}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchDoctor">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'stocks'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Orqanizator" aria-describedby="basic-addon1" v-model="stocks.organizer">
                  </div>
                  <span class="validation" v-if="errors_stocks_search.organizer">{{errors_stocks_search.organizer}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="stocks.date_start" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_stocks_search.date_start">{{errors_stocks_search.date_start}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchStocks">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'reviews'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Həkim adı" aria-describedby="basic-addon1" v-model="reviews.doctor_name">
                  </div>
                  <span class="validation" v-if="errors_reviews_search.doctor_name">{{errors_reviews_search.doctor_name}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="reviews.datetime" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_reviews_search.datetime">{{errors_reviews_search.datetime}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchReviews">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'questions'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Həkim adı" aria-describedby="basic-addon1" v-model="questions.doctor_name">
                  </div>
                  <span class="validation" v-if="errors_questions_search.doctor_name">{{errors_questions_search.doctor_name}}</span>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="questions.q_datetime" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_questions_search.q_datetime">{{errors_questions_search.q_datetime}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchQuestions">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'feedback'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-8 col-sm-8">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="feedback.datetime" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_feedback_search.datetime">{{errors_feedback_search.datetime}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchFeedback">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'enterprises'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Ad" aria-describedby="basic-addon1" v-model="enterprises.name">
                  </div>
                  <span class="validation" v-if="errors_enterprises_search.name">{{errors_enterprises_search.name}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="number" class="form-control" placeholder="Qeydiyyat nömrəsi" aria-describedby="basic-addon1" v-model="enterprises.code">
                  </div>
                  <span class="validation" v-if="errors_enterprises_search.code">{{errors_enterprises_search.code}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Telefon" aria-describedby="basic-addon1" v-model="enterprises.phone">
                  </div>
                  <span class="validation" v-if="errors_enterprises_search.phone">{{errors_enterprises_search.phone}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <button type="button" class="btn-effect" @click = "searchEnterprises">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'donate'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-8 col-sm-8">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="donate.datetime" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_donate_search.datetime">{{errors_donate_search.datetime}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <button type="button" class="btn-effect" @click = "searchDonate">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'promocode'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Orqanizator" aria-describedby="basic-addon1" v-model="promocode.organizer">
                  </div>
                  <span class="validation" v-if="errors_promocode_search.organizer">{{errors_promocode_search.organizer}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Başlama tarixi"
                                    v-model="promocode.date_start" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_promocode_search.date_start">{{errors_promocode_search.date_start}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Bitmə tarixi"
                                    v-model="promocode.date_end" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_promocode_search.date_end">{{errors_promocode_search.date_end}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <button type="button" class="btn-effect" @click = "searchPromocode">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else-if="isnormal === 'news'">
    <div class="search">
      <div class="block">
        <form style="width:100%;">
          <div class="search-blog search-page">
            <div class="row">
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="number" class="form-control" placeholder="ID" aria-describedby="basic-addon1" v-model="news.id">
                  </div>
                  <span class="validation" v-if="errors_news_search.id">{{errors_news_search.id}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="input-group mbx-10">
                  <div>
                    <input type="text" class="form-control" placeholder="Başlıq" aria-describedby="basic-addon1" v-model="news.headline">
                  </div>
                  <span class="validation" v-if="errors_news_search.headline">{{errors_news_search.headline}}</span>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <div class="validate-block new-addition">
                  <div class="upload-date">
                    <div class="input-group input-up">
                      <div class="textup">
                        <datepicker class="form-control" placeholder="Tarix"
                                    v-model="news.datetime" :language="az"></datepicker>
                      </div>
                      <span class="validation" v-if="errors_news_search.datetime">{{errors_news_search.datetime}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-3">
                <button type="button" class="btn-effect" @click = "searchNews">
                  <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12" v-else>
    <div class="search">
      <div class="block d-block 3333">
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
          </div>
          <div class="col-md-9 col-sm-8">
            <div class="search-blog">
              <div class="input-group mbx-10">
                <input type="text" class="form-control" placeholder="Axtarış sözü" aria-describedby="basic-addon1">
              </div>
              <button type="submit" class="btn-effect">
                <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {axios} from './config/axios';
  import Multiselect from 'vue-multiselect';
  import Datepicker from 'vuejs-datepicker';
  import {az} from 'vuejs-datepicker/dist/locale';
  export default {
    name: "Search",
    data: function(){
      return {
        az:az,
        searchInput: '',
        isnormal: 'doct',
        SearchType: [
          {id:1, name:'Qeydiyyat nömrəsinə görə'},
          {id:2, name:'Əraziyə görə'},
          {id:3, name:'Kartla ödəniş'},
          {id:4, name:'Nəğd ödəniş'},
          {id:5, name:'Premium'}
        ],
        isSearched: 0,
          page: 1,
        doctorSpecialists: [],
        doctor: {
            name: '',
            email: '',
            number: '',
            code: '',
            specialist: ''
        },
        stocks: {
            organizer: '',
            date_start: ''
         },
        reviews: {
            doctor_name: '',
            datetime: ''
        },
        questions: {
            doctor_name: '',
            q_datetime: ''
        },
        feedback: {
            datetime: ''
        },
        donate: {
            datetime: ''
        },
        enterprises: {
            name: '',
            code: '',
            phone: ''
        },
        promocode: {
            organizer: '',
            date_start: '',
            date_end: ''
        },
        news: {
            id: '',
            headline: '',
            datetime: ''
        },
        errors_doctor_search:{
            name:false,
            email:false,
            number:false,
            code:false,
            specialist:false
        },
        errors_reviews_search:{
            doctor_name:false,
            datetime:false
        },
        errors_questions_search:{
            doctor_name:false,
            q_datetime:false
        },
        errors_feedback_search:{
            datetime:false
        },
        errors_donate_search:{
            datetime:false
        },
        errors_stocks_search:{
            organizer: false,
            date_start: false
        },
        errors_news_search:{
            id: false,
            headline: false,
            datetime: false
        },
        errors_enterprises_search:{
            name: false,
            code: false,
            phone: false
        },
        errors_promocode_search: {
          organizer: false,
          date_start: false,
          date_end: false
        },
      }
    },
    components: {
      Multiselect,
      Datepicker
    },
    props: {
      isnormalsearch: String,
      errors_doctor: Object,
      errors_reviews:  Object,
      errors_questions: Object,
      errors_feedback: Object,
      errors_donate: Object,
      errors_stocks: Object,
      errors_news: Object,
      errors_enterprises: Object,
      errors_promocode: Object
    },
    mounted: function () {
      this.isnormal = this.$props.isnormalsearch;
      this.getDoctorSpecialist();
    },
    watch: {
        errors_doctor: function(newVal, oldVal) {
            this.errors_doctor_search = newVal;
        },
        errors_reviews: function(newVal, oldVal) {
            this.errors_reviews_search = newVal;
        },
        errors_questions: function(newVal, oldVal) {
            this.errors_questions_search = newVal;
        },
        errors_feedback: function(newVal, oldVal) {
            this.errors_feedback_search = newVal;
        },
        errors_donate: function(newVal, oldVal) {
            this.errors_donate_search = newVal;
        },
        errors_stocks: function(newVal, oldVal) {
            this.errors_stocks_search = newVal;
        },
        errors_news: function(newVal, oldVal) {
            this.errors_news_search = newVal;
        },
        errors_enterprises: function(newVal, oldVal) {
            this.errors_enterprises_search = newVal;
        },
        errors_promocode: function(newVal, oldVal) {
          this.errors_promocode_search = newVal;
        }
    },
    methods: {
        searchQuestions() {
            this.$emit('SearchRequestedQuestions', this.page, this.questions, this.isSearched = 1);
        },

        searchReviews() {
            this.$emit('SearchRequestedReviews', this.page, this.reviews, this.isSearched = 1);
        },

        searchDoctor() {
          this.$emit('SearchRequested', this.page, this.doctor, this.isSearched = 1);
        },

        searchFeedback() {
            this.$emit('SearchRequestedFeedback', this.page, this.feedback, this.isSearched = 1);
        },

        searchDonate() {
            this.$emit('SearchRequestedDonate', this.page, this.donate, this.isSearched = 1);
        },

        searchStocks() {
            this.$emit('SearchRequestedStocks', this.page, this.stocks, this.isSearched = 1);
        },

        searchNews() {
            this.$emit('SearchRequestedNews', this.page, this.news, this.isSearched = 1);
        },

        searchEnterprises() {
            this.$emit('SearchRequestedEnterprises',this.page, this.enterprises, this.isSearched = 1);
        },

        searchPromocode() {
          this.$emit('SearchRequestedPromocode',this.page, this.promocode, this.isSearched = 1);
        },

        getDoctorSpecialist: function () {
          axios.get('/doctors/specialists',this.doctorSpecialist).then(
              response=> {
                  if (response.data.status !== 200) {
                  }
                  for(let i in response.data.data){
                      this.doctorSpecialists.push({
                          id: response.data.data[i].id,
                          name: response.data.data[i].name
                      })
                  }
              })
      }
    }
  }
</script>

<style scoped>

</style>
