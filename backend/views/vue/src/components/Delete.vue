<template>
    <div v-if="isnormaldelete === 1">
        <span v-if="user_role === 'superadmin'">
                <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message"
                        @click="deleteHardAll()">
                    <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                </button>
            </span>
        <span v-else></span>
        <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="deleteAll()">
            <span><i class="fa fa-trash" aria-hidden="true"></i></span>
        </button>
    </div>

    <div v-else-if="isnormaldelete === 0">
        <span v-if="user_role === 'superadmin'">
            <button type="button" class="btn btn-remove-doctor" @click="deleteHardOne()">
                <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
            </button>
        </span>
        <span v-else=""></span>

        <button type="button" class="btn btn-remove-doctor" @click="deleteOne()">
            <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
        </button>
    </div>
</template>

<script>
    import axios_main from "axios";
    import {axios} from "./config/axios";
    import Swal from "sweetalert2";

    export default {
        name: "Delete",
        data: function () {
            return {
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)',
                deletedItems: ''
            }
        },
        props: {
            isnormaldelete: Number,
            deleted_items: String,
            deletefrom: String
        },
        watch:{
            user_role: function(newVal, oldVal) {
                this.user_role = newVal;
            },
            deleted_items: function(newVal, oldVal) {
                console.log('changed2', newVal)
                this.deletedItems = newVal;
            }
        },
        mounted: function () {
            this.getUser();
            console.log(this.$props.isnormaldelete, this.$props.deleted_items, this.$props.deletefrom)
        },
        methods: {
            getUser(){
                axios_main.get('/api/site/user').then(
                    response=>{
                        this.user_role = response.data.data.role.item_name;
                    }
                )
            },

            delete_custom() {
                this.$emit('DeleteRequested');
            },

            deleteAll() {
                let id = '';
                id = this.deletedItems;
                if (id) {
                    id = JSON.parse(id);
                }
                else {
                    id = [];
                }
                if(id.length >0){
                    Swal.fire({
                        title: "Bu əməliyyatı icra etməyə əminsiniz?",
                        text: "Əgər silsəniz, geri qaytara bilməyəcəksiniz!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                        .then((result) => {
                            if (result.value) {
                                axios.post('/doctors/all-delete',{id}).then(
                                    response=> {
                                        if (response.data.status === 200) {
                                            this.$emit('DeleteRequested');
                                        }
                                        else {
                                            return Swal.fire({
                                                type: 'error',
                                                text: response.data.message()
                                            })
                                        }
                                    });
                            }
                        });
                }
                else {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Bildiriş',
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Oldu!',
                        text: 'Heç bir həkim seçməmisiniz!',
                    })
                }
            },

            deleteHardAll() {
                console.log('deleteHardAll delete');
                let id = '';
                id = this.deletedItems;
                if (id) {
                    id = JSON.parse(id);
                }
                else {
                    id = [];
                }
                if(id.length >0){
                    Swal.fire({
                        title: "Bu əməliyyatı icra etməyə əminsiniz?",
                        text: "Əgər silsəniz, geri qaytara bilməyəcəksiniz!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                        .then((result) => {
                            if (result.value) {
                                axios.post('/doctors/all-base-delete',{id}).then(
                                    response=> {
                                        if (response.data.status === 200) {
                                            this.$emit('DeleteRequested');
                                        }
                                        else {
                                            return Swal.fire({
                                                type: 'error',
                                                text: response.data.message()
                                            })
                                        }
                                    });
                            }
                        });
                }
                else {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Bildiriş',
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Oldu!',
                        text: 'Heç bir həkim seçməmisiniz!',
                    })
                }
            },

            deleteOne() {
                console.log('doctor_id', this.deleted_items);
             },

            deleteHardOne() {
                console.log('doctor_id2', this.deleted_items);
            }
        }
    }
</script>

<style scoped>

</style>
