<template>
    <div class="search-relative">
        <input type="text" class="form-control" v-model="searchText" @keyup="search()">
        <div class="result-vue">
            <ul class="list-item-result" v-html="searchArrs">
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                searchText: '',
                searchArrs: '',
            }
        },
        methods: {
            search() {
                axios.post('api/search-with-vue', {
                    name: this.searchText
                })
                .then((response) => {
                    this.searchArrs = response.data;
                });
            }
        }
    }
</script>