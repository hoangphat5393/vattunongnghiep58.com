<template>
    <div>
        <div class="row">
            <product v-for="product in products" :product="product" :key="product.id"></product>
        </div>
        <pagination v-bind:pagination="pagination" v-on:click.native="getUsers(pagination.current_page)" :offset="4"></pagination>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                products: [],
                counter: 0,
                pagination: {
                    total: 0,
                    per_page: 2,
                    last_page: 0,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4
            }
        },
        mounted() {
            this.getUsers(this.pagination.current_page);
        },
        methods: {
            getUsers (page) {
                axios.get('api/test-pagination?page='+page)
                    .then((response) => {
                        this.products = response.data.data.data;
                        this.pagination = response.data.pagination;
                    })
            }
        }
    }
</script>