<template>
    <div class="appointment statics blog">
        <breadcrumb name="Randevu" icon="fa-calendar" :routes="[{'name':'Randevu'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="static-blocks">
                    <div class="block" v-for="item in weekdays" @click="getScheduleByWeekday(item.weekday)" :class="PostData.weekday==item.weekday ? 'active' : '' ">
                        {{item.name}}
                    </div>
                </div>
                <div class="appoint-content">
                    <div class="block">
                        <div class="new-addition">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="general-info">
                                        <div class="validate-block">
                                            <div class="input-group input-up input-up-interval">
                                                <label>
                                                  <span>İnterval seçin*</span>
                                                </label>
                                                <multiselect v-model="PostData.Interval"
                                                             label="name"
                                                             selectedLabel=""
                                                             deselectGroupLabel=""
                                                             deselectLabel=""
                                                             select-label=""
                                                             :reset-after="false"
                                                             :allow-empty="false"
                                                             :options="intervals">
                                                </multiselect>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="general-info">
                                        <div class="validate-block">
                                            <div class="input-group input-up">
                                                <label>
                                                  <input type="text" v-model="PostData.StartTime"
                                                         v-mask="timeMask_custom"
                                                              placeholder=" " class="form-control inputText">
                                                  <span>İşin başlanma vaxtı*</span></label>
                                            </div>
                                            <span class="validation" v-if="errors.start_time">{{errors.start_time}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="general-info">
                                        <div class="validate-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="text" placeholder=" " v-mask="timeMask_custom"
                                                           v-model="PostData.EndTime" class="form-control inputText">
                                                    <span>İşin bitmə vaxtı*</span>
                                                </label>
                                            </div>
                                            <span class="validation" v-if="errors.end_time">{{errors.end_time}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="general-info">
                                        <div class="validate-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="text" placeholder=" " v-mask="timeMask_custom"
                                                           v-model="PostData.breakTimeStart" class="form-control inputText">
                                                    <span>Nahar başlanma vaxtı*</span>
                                                </label>
                                            </div>
                                            <span class="validation" v-if="errors.breakfast_time_start">{{errors.breakfast_time_start}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="general-info">
                                        <div class="validate-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="text" placeholder=" "  v-mask="timeMask_custom"
                                                           v-model="PostData.breakTimeEnd" class="form-control inputText">
                                                    <span>Nahar bitmə vaxtı*</span>
                                                </label>
                                            </div>
                                            <span class="validation" v-if="errors.breakfast_time_end">{{errors.breakfast_time_end}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <div class="search-blog promo">
                            <button type="submit" class="btn-effect" @click="submitFile()">YADDA SAXLA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import {axios} from "../config/doctor_axios";
    import Swal from 'sweetalert2';
    export default {
        components: {
            Multiselect
        },
        name: "appointment",
        data(){
            return {
                PostData:{
                    Interval:{ name: '1 saat', value:'60', interval:'60'},
                    weekday: '1',
                    StartTime: '',
                    EndTime:'',
                    breakTimeStart: '',
                    breakTimeEnd: ''
                },
                intervals:[],
                weekdays:[
                    {name:'Bazar ertəsİ', weekday:1},
                    {name:'çərşənbə axşamı', weekday:2},
                    {name:'çərşənbə', weekday:3},
                    {name:'cümə axşamı', weekday:4},
                    {name:'cümə ', weekday:5},
                    {name:'şənbə ', weekday:6},
                    {name:'Bazar ', weekday:7},
                ],
                timeMask_custom: this.timeMask,
                errors:{
                  start_time:false,
                  end_time:false,
                  breakfast_time_start:false,
                  breakfast_time_end:false
                }
            }
        },
        mounted(){
            this.getInterval();
        },
        methods:{
            submitFile() {
                let formdata= new FormData();
                formdata.append('StartTime',this.PostData.StartTime);
                formdata.append('EndTime',this.PostData.EndTime);
                formdata.append('breakTimeStart',this.PostData.breakTimeStart);
                formdata.append('breakTimeEnd',this.PostData.breakTimeEnd);
                formdata.append('Interval',this.PostData.Interval.interval);
                formdata.append('weekday',this.PostData.weekday);
                axios.post('/appointment/work-time-setting', formdata)
                  .then (
                    res =>{
                      if(res.data.status==200){
                          return Swal.fire({
                              type: 'success',
                              text: res.data.message,
                              showConfirmButton: false,
                          })
                      }
                    })
                  .catch(error => {
                    if (error.response) {
                      if (error.response.data.status === 400) {
                        if (error.response.data.data.StartTime) {
                          this.errors.start_time = error.response.data.data.StartTime[0]
                        }
                        else {
                          this.errors.start_time = false;
                        }

                        if (error.response.data.data.EndTime) {
                          this.errors.end_time = error.response.data.data.EndTime[0]
                        }
                        else {
                          this.errors.end_time = false;
                        }

                        if (error.response.data.data.breakTimeStart) {
                          this.errors.breakfast_time_start = error.response.data.data.breakTimeStart[0]
                        }
                        else {
                          this.errors.breakfast_time_start = false;
                        }

                        if (error.response.data.data.breakTimeEnd) {
                          this.errors.breakfast_time_end = error.response.data.data.breakTimeEnd[0]
                        }
                        else {
                          this.errors.breakfast_time_end = false;
                        }
                      }
                    }
                  });
            },
            getInterval() {
                axios.get('/appointment/time-interval-list').then(
                    response=> {
                        this.intervals = response.data.data;
                        this.getScheduleByWeekday(1)
                    }
                )
            },
            getScheduleByWeekday(weekday) {
                this.errors.start_time = false;
                this.errors.end_time = false;
                this.errors.breakfast_time_start = false;
                this.errors.breakfast_time_end = false;
                axios.get('/appointment/work-time-info?weekday='+weekday).then(
                    response=> {
                        if(response.data.data === null){
                            this.PostData = {
                                Interval:{ name: '1 saat', value:'60', interval:'60'},
                                weekday: weekday,
                                StartTime: '',
                                EndTime:'',
                                breakTimeStart: '',
                                breakTimeEnd: ''
                            };
                            return;
                        }
                        this.PostData=response.data.data;
                        this.PostData.weekday = weekday;
                        for(let i of this.intervals){
                            this.PostData.Interval = (parseInt(i.interval) == parseInt(this.PostData.Interval)) ? i : this.PostData.Interval;
                        }

                    }
                )
            },
            timeMask: function (value) {
                const hours = [
                  /[0-2]/,
                  value.charAt(0) === '2' ? /[0-3]/ : /[0-9]/,
                ];
                const minutes = [/[0-5]/, /[0-9]/];
                return value.length > 2
                  ? [...hours, ':', ...minutes]
                  : hours;
            }
        },

    }
</script>
