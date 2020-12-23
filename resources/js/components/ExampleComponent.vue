<template>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Тестовое задание TEST</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-dark">
                                    <thead>
                                    <tr class="pointer">
                                        <th :class="sorted == 'age' ? 'active' : ''" v-on:click="sortirovka('age')">age</th>
                                        <th :class="sorted == 'eyeColor' ? 'active' : ''" v-on:click="sortirovka('eyeColor')">eyeColor</th>
                                        <th :class="sorted == 'name' ? 'active' : ''" v-on:click="sortirovka('name')">name</th>
                                        <th :class="sorted == 'gender' ? 'active' : ''" v-on:click="sortirovka('gender')">gender</th>
                                        <th :class="sorted == 'company' ? 'active' : ''" v-on:click="sortirovka('company')">company</th>
                                        <th :class="sorted == 'email' ? 'active' : ''" v-on:click="sortirovka('email')">email</th>
                                        <th :class="sorted == 'phone' ? 'active' : ''" v-on:click="sortirovka('phone')">phone</th>
                                        <th :class="sorted == 'address' ? 'active' : ''" v-on:click="sortirovka('address')">address</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="active_and_sorted">
                                    <tr v-for="element in active_and_sorted" :key="element.id">
                                        <td>{{element.age}}</td>
                                        <td>{{element.eyeColor}}</td>
                                        <td>{{element.name}}</td>
                                        <td>{{element.gender}}</td>
                                        <td>{{element.company}}</td>
                                        <td>{{element.email}}</td>
                                        <td>{{element.phone}}</td>
                                        <td>{{element.address}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-dark btn-sm" v-if="max_count_at_page === false" v-on:click="addMore">Read More</button>
                                <button class="btn btn-dark btn-sm" v-if="pageSize > 2" v-on:click="clearBook">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: 'ExampleComponent',
        props: {
            url: String,
        },
        data(){
            return {
                active_and_sorted: null,
                sortDesc: 1,
                pageSize: 2,
                sorted: 'age',
                max_count_at_page: false,
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData(){
                let data = new FormData();
                data.append('sortDesc', this.sortDesc);
                data.append('pageSize', this.pageSize);
                data.append('sorted', this.sorted);
                axios.post(this.url + '/api/getBook', data).then(res=>{
                    this.active_and_sorted = res.data.active_and_sorted;
                    this.sortDesc = res.data.sortDesc;
                    this.pageSize = res.data.pageSize;
                    this.sorted = res.data.sorted;
                    this.max_count_at_page = res.data.max_count_at_page;
                });

            },
            clearBook(){
                this.sortDesc = 1;
                this.pageSize = 2;
                this.sorted = 'age';
                this.max_count_at_page = false;
                this.$nextTick(function () {
                    this.getData();
                });
            },
            addMore(){
                this.pageSize = parseInt(this.pageSize) + 2;
                this.$nextTick(function () {
                    this.getData();
                });
            },
            sortirovka(sortName){
                if(this.sorted == sortName){
                    this.sorted = sortName;
                    if(this.sortDesc == 1){
                        this.sortDesc = 2;
                    }else{
                        this.sortDesc = 1;
                    }
                }else{
                    this.sorted = sortName;
                    this.sortDesc = 1;
                }
                this.$nextTick(function () {
                    this.getData();
                });
            },
        },
    }
</script>
<style lang="css">
    .pointer>th{
        cursor: pointer;
    }
    .pointer>.active{
        color: red;
    }
</style>
